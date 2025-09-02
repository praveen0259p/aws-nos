<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Scheme;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormFormfield;
use App\Models\FormFieldOption;
use App\Models\FormSubmission;
use App\Models\PmuIrProposalList;
use App\Models\InspectionDetail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiController extends Controller
{

    public function steps(Request $request)
    {
        try {
            $steps = FormFormfield::select('steps')->where(['form_id' => $request->form_id, 'active' => 1])->orderBy('steps')->distinct()->pluck('steps');
            return response()->json(['steps' => $steps], 200);
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
            $filtered = array_filter($options->values, fn($option) => $option['StateCode'] == $request->StateCode);
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
        $stepsArray = FormFormfield::select('steps')->where(['form_id' => $request->form_id])->distinct()->pluck('steps');
        $validated = $request->validate([
            'scheme_id' => 'required|exists:schemes,scheme_id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|in:' . implode(',', $stepsArray->toArray()),
            'ngo_unique_id' => 'required|exists:proposal,ngo_unique_id',
            'acknowledgement_number' => 'required|exists:proposal,acknowledgement_number',
            'scheme_project_type' => 'required|in:'.$this->projectType(),
        ]);
        try {
            $scheme = Scheme::with([
                'forms.fields' => function ($query) use ($request) {
                    $query->with([
                        'option',
                        'formSubmission' => function ($q) use ($request) {
                            $q->where([
                                'acknowledgement_number' => $request->acknowledgement_number,
                                'ngo_unique_id' => $request->ngo_unique_id
                            ]);
                        },
                        'children' => function ($childQuery) use ($request) {
                            $childQuery->with([
                                'option',
                                'formSubmission' => function ($q) use ($request) {
                                    $q->where([
                                        'acknowledgement_number' => $request->acknowledgement_number,
                                        'ngo_unique_id' => $request->ngo_unique_id
                                    ]);
                                }
                            ]);
                        }
                    ])
                        ->wherePivot('active', 1);
                },
            ])->where('active', 1)->findOrFail($validated['scheme_id']);
            if (!$scheme) {
                return response()->json([
                    'message' => 'Scheme not found.'
                ], 404);
            }
            $form = $scheme->forms->where('active', 1)->firstWhere('id', $validated['form_id']);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found under the specified scheme.'
                ], 404);
            }

            $projectType = (string)$request->scheme_project_type;
            $formFields = $form->fields->filter(function ($field) use ($validated, $projectType) {
                if ($field->pivot->steps != $validated['steps']) {
                    return false;
                }
                if ($field->pivot->active != 1) {
                    return false;
                }
                if (!empty($projectType)) {
                    $types = array_map('trim', explode(',', $field->scheme_project_type ?? ''));
                    if (!in_array($projectType, $types, true)) {
                        return false;
                    }
                    return true;
                }
            })
                ->values()
                ->map(function ($field) {
                    $submission = optional($field->formSubmissions)->first();
                    //$field->response = optional($submission)->field_response;
                    return $field;
                });
            $proposalList = PmuIrProposalList::where('acknowledgement_number', $request->acknowledgement_number)->get();

            return response()->json([
                'form_fields' => $formFields->isNotEmpty() ? $formFields : null,
                'proposal_list' => $proposalList->isNotEmpty() ? $proposalList : [],
                'message' => $formFields->isNotEmpty() ? null : 'No Form Fields Found',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the form.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function submit(Request $request)
    {
        $lastStep = FormFormfield::where(['form_id' => $request->form_id, 'active' => 1])->max('steps');
        $stepsArray = FormFormfield::select('steps')->where(['form_id' => $request->form_id, 'active' => 1])->distinct()->pluck('steps');
        $request->validate([
            'scheme_id' => 'required|exists:schemes,scheme_id',
            'form_id' => 'required|exists:forms,id',
            'steps' => 'required|integer|min:1|in:' . implode(',', $stepsArray->toArray()),
            'ngo_unique_id' => 'required|exists:proposal,ngo_unique_id',
            'acknowledgement_number' => 'required|exists:proposal,acknowledgement_number',
        ]);
        try {
            $fieldsInput = $request->input('fields', []);
            $fieldValues = collect($fieldsInput)->mapWithKeys(function ($item) {
                return [$item['field_id'] => $item['value']];
            });
            $fieldIds = $fieldValues->keys()->toArray();
            $formFields = FormField::whereIn('id', $fieldIds)->get();
            $validationRules = $customAttributes = $transformedInput = [];
            foreach ($formFields as $field) {
                $fieldId = $field->id;
                $fieldName = $field->name;
                $value = $fieldValues->get($fieldId);
                $rules = is_array($field->validation_rule)? $field->validation_rule: explode('|', $field->validation_rule);
                $validationRules[$fieldName] = implode('|', $rules);
                $customAttributes[$fieldName] = $field->label ?? "Field {$fieldId}";
                if ($value === $fieldName) {
                    //Log::warning("Field ID: {$fieldId} has a value equal to its name. Value: {$value}");
                    $value = null;
                }
                $transformedInput[$fieldName] = $value;   
                Log::info("Field ID: {$fieldId}, Label: {$field->label}, Value: " . print_r($value, true));
            }
            $messages = config('validationmessages') ?? [];
            $validator = Validator::make($transformedInput, $validationRules, $messages, $customAttributes);
            $validator->sometimes(['rent_month', 'rent_area_space'], 'numeric', function ($transformedInput) {
                return $transformedInput->rented_owned === 'Rented';
            });
            Log::info('Transformed Input:' . PHP_EOL . print_r($transformedInput, true));
            Log::info('Validation Rule:' . PHP_EOL . print_r($validationRules, true));
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            try {
                $results = [];
                foreach ($request->fields as $field) {
                    $field_response = is_array($field['value']) ? json_encode($field['value']) : $field['value'];
                    $record = FormSubmission::updateOrCreate(
                        [
                            'ngo_unique_id' => $request->ngo_unique_id,
                            'acknowledgement_number' => $request->acknowledgement_number,
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
                $affectedRows=PmuIrProposalList::where('acknowledgement_number', $request->acknowledgement_number)->update(['status' => $status, 'status_changed_by' => JWTAuth::parseToken()->authenticate()->id, 'status_updated_at' => now()]);
                if ($status === 2 && $affectedRows>0) {
                   $inspection= InspectionDetail::create([
                        'acknowledgement_number' => $request->acknowledgement_number,
                        'inspection_id' => $this->generateInspectionId($request),
                        'status' => $status,
                        'created_by' => JWTAuth::parseToken()->authenticate()->id,
                    ]);
                    FormSubmission::where('acknowledgement_number', $request->acknowledgement_number)
                    ->where('ngo_unique_id', $request->ngo_unique_id)
                    ->where('form_id', $request->form_id)
                    ->where('user_id', JWTAuth::parseToken()->authenticate()->id)
                    ->update(['inspection_id' => $inspection->inspection_id]);
                }
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
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('FormSubmission failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'An error occurred while submitting the form.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //testing 
    private function projectType()
    {
        $stepsArray = FormField::select('scheme_project_type')->distinct()->pluck('scheme_project_type');
        $totalSteps = $stepsArray->toArray();
        $allTypes = [];
        foreach ($totalSteps as $step) {
            $types = explode(',', $step);  
            $allTypes = array_merge($allTypes, $types);
        }
        $uniqueTypes = array_unique($allTypes);
        sort($uniqueTypes, SORT_NUMERIC);
        $largestValue = implode(',', $uniqueTypes);
        return $largestValue;
        //return response()->json($largestValue, 200);
    }
    private function generateInspectionId($request)
    {
        $results=InspectionDetail::where(['acknowledgement_number' => $request->acknowledgement_number])->get();
        if($results->count() > 0)
        {
            return (int) $results->last()->inspection_id+1;
        }else{
            $parts = explode('/',($request->acknowledgement_number));
            $lastPart= end($parts).'1';
            return (int) $lastPart;
        }
    }
    public function stepsCheck(Request $request)
    {

        //$url = "https://example.com/api/send-status";
        $acknowledgement_number='KA/KA/00039933/SC/04-25/72127';
        $inspection = InspectionDetail::where('acknowledgement_number', $acknowledgement_number)->with(['proposal:acknowledgement_number,scheme_project_name,process_id,form_type,financial_year'])->first();
        $postData = [
            'pfrId'         => $inspection->inspection_id,
            'Ispfrdrafted'  => $inspection->status ==2 ?false:true,
            'boUserId'      => $inspection->created_by,
            'filleddate'     => Carbon::parse($inspection->created_at)->format('Y-m-d'),
            'ackNumber'     => $inspection->acknowledgement_number,
            'boProcessid'   => $inspection->proposal->process_id ?? null,
            'schemeName'    => $inspection->proposal->form_type ?? null,
            'financialyear' => $inspection->proposal->financial_year ?? null,
        ];
        return response()->json($postData,200);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ]);
        }
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            echo "cURL Error: " . $error;
        } else {
            echo "Response: " . $response;
        }
    }
    private function Login(){

    }
    public function sendStatus($token=null,$inspection)
    {
        //$url = "https://example.com/api/send-status";
        $acknowledgement_number='KA/KA/00039933/SC/04-25/72127';
        $inspection = InspectionDetail::where('ackNumber', $acknowledgement_number)->with('proposal')->first();
        return response()->json($inspection,200);
        $postData = [
            'pfrId'         => '12345',
            'Ispfrdrafted'  => true,
            'boUserId'      => '67890',
            'ackNumber'     => 'ACK123456',
            'boProcessid'   => 'BPID98765',
            'fielddate'     => '2025-08-31',
            'schemeName'    => 'Sample Scheme',
            'financialyear' => '2025-2026'
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ]);
        }
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            echo "cURL Error: " . $error;
        } else {
            echo "Response: " . $response;
        }
    }
}
