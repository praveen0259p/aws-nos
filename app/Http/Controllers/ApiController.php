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
    
    public function steps()
    {
        try {
            $steps=FormField::distinct()->pluck('steps');
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
            'scheme_id' => 'required|exists:schemes,id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|in:'.implode(',',$stepsArray->toArray()),
            'project_id'=> 'required|exists:pmu_ir_proposal_lists,project_id',
            'scheme_project_type'=>'required|in:1,2,3',
        ]);
        try {
           // $scheme = Scheme::with(['forms.fields.option','forms.fields.formSubmission'])->where('active',1)->findOrFail($validated['scheme_id']);
            $scheme = Scheme::with([
                'forms.fields.option',
                'forms.fields.formSubmission' => function ($q) use ($request) {
                    $q->where('project_id', $request->project_id);
                }
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
            //$form = $form->fields->where('steps', $validated['steps'])->where('active',1)->sortBy('order')->values(); 
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
                $field->response = optional($submission)->field_response;
                return $field;
            });
            $proposalList = PmuIrProposalList::where('project_id', $request->project_id)->get();
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
            'scheme_id' => 'required|exists:schemes,id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|min:1|in:'.implode(',',$stepsArray->toArray()),
            'project_id'=>'required|exists:pmu_ir_proposal_lists,project_id',
        ]); 
        try {
            $scheme = Scheme::with(['forms.fields.option'])->where('active',1)->findOrFail($validated['scheme_id']);
            $form = $scheme->forms->where('active',1)->firstWhere('id', $validated['form_id']);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found under the specified scheme.'
                ], 404);
            }
            $form = $form->fields->where('steps', $validated['steps'])->where('active',1)->sortBy('order')->values();
            $validationRules=[];
            $customAttributes  = [];
            foreach ($form as $field) {
                $validationRules[$field->id] = implode('|',$field->validation_rule);
                $customAttributes [$field->id] = $field->label ?? $field->id;
            }
            $input = [];
            
            foreach ($request->fields as $item) {
                if (isset($item['field_id'], $item['value'])) {
                    $input[$item['field_id']] = $item['value'];
                }
            }
            $messages = config('validationmessages');
            $request->merge($input);
            $request->validate($validationRules,$messages,$customAttributes);
            //return response()->json($request->all(),200);
            try {
                $results = [];
                $fieldsToEncode=[3,7];
                foreach ($request->fields as $field) {
                    $field_response = in_array($field['field_id'], $fieldsToEncode, true)? json_encode($field['value']): $field['value'];
                    $record= FormSubmission::updateOrCreate(
                        [
                            'project_id' => $request->project_id,
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
                PmuIrProposalList::where('project_id',$request->project_id)->update(['status'=>$status,'status_changed_by'=>JWTAuth::parseToken()->authenticate()->id,'status_updated_at'=>now()]);
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
    // public function stepsCheck(Request $request)
    // {
    //     $stepsArray = FormField::select('steps')->where([ 'scheme_id'=>5,'form_id'=>5,'active'=>1])->distinct()->pluck('steps');
    //     $totalSteps=$stepsArray->toArray();
    //     //$completedSteps=e$stepsArray->toArray();
    //     return response()->json(end($totalSteps),200);
    //     $lastStepSubmitted = FormSubmission::where(['Ngo_Unique_Id'=>$request->ngo_id])->orderByDesc('steps')->first();
    //     $firstStep=$stepsArray->toArray();
    //     $stepToUse = $request->steps ?? ($lastStepSubmitted + 1) ?? 1;
    //     $validated=$request->validate([
    //         'scheme_id' => 'required|exists:schemes,id',
    //         'form_id' => 'required|exists:forms,id',
    //         'steps' => 'sometimes|required|integer|in:'.implode(',',$stepsArray->toArray()),
    //         'ngo_id'=> 'required',
    //     ]);
    //     //return response()->json($request->all(), 200);
    //     try {
    //        // $scheme = Scheme::with(['forms.fields.option','forms.fields.formSubmission'])->where('active',1)->findOrFail($validated['scheme_id']);
    //        $scheme = Scheme::with([
    //             'forms.fields.option',
    //             'forms.fields.formSubmission' => function ($q) use ($request) {
    //                 $q->where('Ngo_Unique_Id', $request->ngo_id);
    //             }
    //         ])->where('active', 1)->findOrFail($validated['scheme_id']);
    //         if (!$scheme) {
    //             return response()->json([
    //                 'message' => 'Scheme not found.'
    //             ], 404);
    //         }
    //         $form = $scheme?->forms->where('active',1)->firstWhere('id', $validated['form_id']);
    //         if (!$form) {
    //             return response()->json([
    //                 'message' => 'Form not found under the specified scheme.'
    //             ], 404);
    //         }
    //         //$form = $form->fields->where('steps', $validated['steps'])->where('active',1)->sortBy('order')->values();
    //         $formFields = $form->fields->where('steps',$stepToUse)->where('active', 1)->values()->whereNotIn('id', $request->excludeIds)
    //         ->map(function ($field) {
    //             $submission = optional($field->formSubmissions)->first();
    //             $field->response = optional($submission)->field_response;
    //             return $field;
    //         });
    //         return response()->json($formFields, 200);
    //     }catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'An error occurred while fetching the form.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
