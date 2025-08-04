<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Scheme;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormFieldOption;
use App\Models\FormSubmission;
use App\Models\PmuIrProposalList;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;



class ApiController extends Controller
{
    
    public function steps(Request $request)
    {
        try {
            $steps=FormField::select('steps')->where([ 'scheme_id'=>$request->scheme_id,'form_id'=>$request->form_id,'active'=>1])->distinct()->pluck('steps');
            return response()->json(['steps'=>$steps], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the steps.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function dropdown(Request $request)
    {
       //return response()->json($request->state_code);
        try {
            $options = FormFieldOption::find(4);
            if (!$options) {
                return response()->json(['message' => 'No option found for the given ID.'], 404);
            }
            if (!$request->has('StateCode')) {
                return response()->json(['message' => 'Missing state code in request'], 422);
            }
            $filtered = array_filter($options->values, fn($option) => $option['StateCode'] ==$request->StateCode);
            $filtered = array_values($filtered);
            $result = array_map(fn($option) => [
                'id' => $option['DistrictCode'],
                'name' => $option['DistrictName']
            ], $filtered);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getform(Request $request)
    {
        $stepsArray = FormField::select('steps')->where([ 'scheme_id'=>$request->scheme_id,'form_id'=>$request->form_id])->distinct()->pluck('steps');
        $validated=$request->validate([
            'scheme_id' => 'required|exists:schemes,scheme_id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|in:'.implode(',',$stepsArray->toArray()),
            'Ngo_Unique_Id'=> 'required|exists:pmu_ir_proposal_lists,Ngo_Unique_Id',
            'Ack_Number'=> 'required|exists:pmu_ir_proposal_lists,Ack_Number',
            'scheme_project_type'=>'required|in:1,2,3',
        ]);
        try {
            $scheme = Scheme::with([
                'forms.fields' => function ($query) use ($request) {
                    $query->with([
                        'option',
                        'formSubmission' => function ($q) use ($request) {
                            $q->where(['Ack_Number'=>$request->Ack_Number,'Ngo_Unique_Id'=>$request->Ngo_Unique_Id]);
                        },
                        'children' => function ($childQuery) use ($request) {
                            $childQuery->with([
                                'option',
                                'formSubmission' => function ($q) use ($request) {
                                    $q->where(['Ack_Number'=>$request->Ack_Number,'Ngo_Unique_Id'=>$request->Ngo_Unique_Id]);
                                }
                            ]);
                        }
                    ]);
                },
            ])->where('active', 1)->findOrFail($validated['scheme_id']);
            if (!$scheme) {
                return response()->json([
                    'message' => 'Scheme not found.'
                ], 404);
            }
            $form = $scheme?->forms->where('active',1)->firstWhere('id', $validated['form_id']);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found under the specified scheme.'
                ], 404);
            } 
            $projectType = (string)$request->scheme_project_type;
            $formFields = $form->fields
            ->where('steps', $validated['steps'])
            ->where('active', 1)
            ->filter(function ($field) use ($projectType) {
                $types = array_filter(array_map('trim', explode(',', $field->scheme_project_type ?? '')));
                return in_array($projectType, $types, true);
            })
            //->sortBy(fn($field) => (int) $field->order)
            ->values()
             ->map(function ($field) {
                $submission = optional($field->formSubmissions)->first();
                //$field->response = optional($submission)->field_response;
                return $field;
            });
            $proposalList = PmuIrProposalList::where('Ack_Number', $request->Ack_Number)->get();
            return response()->json([
                'form_fields' => $formFields->isNotEmpty() ? $formFields : null,
                'proposal_list' => $proposalList->isNotEmpty() ? $proposalList : [],
                'message' => $formFields->isNotEmpty() ? null : 'No Form Fields Found',
            ], 200);
        
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the form.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
    

    public function submit(Request $request)
    {
        $lastStep = FormField::where(['scheme_id' =>$request->scheme_id,'form_id'=>$request->form_id,'active'=>1])->max('steps');
        $stepsArray = FormField::select('steps')->where([ 'scheme_id'=>$request->scheme_id,'form_id'=>$request->form_id,'active'=>1])->distinct()->pluck('steps');
        $validated=$request->validate([
            'scheme_id' => 'required|exists:schemes,scheme_id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|min:1|in:'.implode(',',$stepsArray->toArray()),
            'Ngo_Unique_Id'=> 'required|exists:pmu_ir_proposal_lists,Ngo_Unique_Id',
            'Ack_Number'=>'required|exists:pmu_ir_proposal_lists,Ack_Number',
            'scheme_project_type'=>'required|in:1,2,3',
        ]); 
        //Log::info('Validated Request Data:', $validated);
        try {
            $scheme = Scheme::with(['forms.fields.option'])->where('active',1)->findOrFail($validated['scheme_id']);
            $form = $scheme->forms->where('active',1)->firstWhere('id', $validated['form_id']);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found under the specified scheme.'
                ], 404);
            }
            $projectType = (string) $validated['scheme_project_type'];
            $form = $form->fields
            ->where('steps', $validated['steps'])
            ->where('active', 1)
            ->filter(function ($field) use ($projectType) {
                $types = array_filter(array_map('trim', explode(',', $field->scheme_project_type ?? '')));
                return in_array($projectType, $types, true);
            })
            ->sortBy('order')
            ->values();
            $validationRules=[];
            $customAttributes  = [];
            foreach ($form as $field) {
                $validationRules[$field->id] = implode('|',$field->validation_rule);
                $customAttributes [$field->id] = $field->label ?? $field->id;
                if ($field->type === 'radio_with_comment') {
                    $commentKey = "{$field->id}_comment";
                    $validationRules[$commentKey] = "required_if:{$field->id},no|string|max:255";
                    $customAttributes[$commentKey] = "{$field->label} Comment";
                }
            }
            $input = [];
            
            foreach ($request->fields as $item) {
                if (isset($item['field_id'], $item['value'])) {
                    $input[$item['field_id']] = $item['value'];
                }
            }
            $messages = config('validationmessages') ?? [];
            $request->merge($input);
            $request->validate($validationRules,$messages,$customAttributes);
            //return response()->json($request->all(),200);
            try {
                $results = [];
                foreach ($request->fields as $field) {
                    $field_response = is_array($field['value']) ? json_encode($field['value']) : $field['value'];
                    //Log::info('Field Response:', ['response' => $field_response]);
                    $record= FormSubmission::updateOrCreate(
                        [
                            'Ngo_Unique_Id' => $request->Ngo_Unique_Id,
                            'Ack_Number' => $request->Ack_Number,
                            'form_id'       => $request->form_id,
                            'scheme_id'     => $request->scheme_id,
                            'steps'         => $request->steps,
                            'field_id'      => $field['field_id'],
                            'user_id'       => JWTAuth::parseToken()->authenticate()->id,
                        ],
                        [
                            'field_response' => $field_response,
                        ]
                    ); 
                    $results[] = [
                        'field_id' => $field['field_id'],
                        'status' => $record->wasRecentlyCreated ? 'inserted' : 'updated',
                    ];
                }
                $status = ((int) $request->steps === (int) $lastStep) ? 2 : 1;
                PmuIrProposalList::where('Ack_Number',$request->Ack_Number)->update(['status'=>$status,'status_changed_by'=>JWTAuth::parseToken()->authenticate()->id,'status_updated_at'=>now()]);
                return response()->json([
                    'steps'   => $request->steps,
                    'success' => true,
                    'message' => $status !== 2 ? "Step {$request->steps} saved as draft." : "Inspection Report Final Submitted Successfully.",
                    'data'    => $results,
                ], 200); 
            } catch (\Exception $e) {
                // Optional: log the error
                Log::error('FormSubmission failed', [ 
                    'field_id' => $field['field_id'],
                    'value'    => $field['value'],
                    'error'    => $e->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing form data.',
                    'error'   => $e->getMessage()
                ], 500);
            }
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }catch (\Exception $e) {
            Log::error('FormSubmission failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'An error occurred while submitting the form.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
