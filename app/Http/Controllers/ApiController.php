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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiController extends Controller
{
    private $eanudannUrl = 'http://10.22.13.209:8005/dbtbharatws/rest/pmu/v1';
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
            'scheme_project_type' => 'required|in:' . $this->projectType(),
        ]);
        $projectType = (string)$request->scheme_project_type;
        try {
            $scheme = Scheme::with([
                'forms.fields' => function ($query) use ($request, $validated, $projectType) {
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
                        ->wherePivot('active', 1)
                        ->wherePivot('steps', $validated['steps'])
                        ->when(!empty($projectType), function ($q) use ($projectType) {
                            $q->whereRaw("FIND_IN_SET(?, scheme_project_type)", [$projectType]);
                        });
                },
            ])->where('active', 1)->findOrFail($validated['scheme_id']);
            $form = $scheme->forms->where('active', 1)->firstWhere('id', $validated['form_id']);
            if (!$form) {
                return response()->json([
                    'message' => 'Form not found under the specified scheme.'
                ], 404);
            }

            $formFields = $form->fields->map(function ($field) {
                // $submission = optional($field->formSubmissions)->first();
                // $field->response = optional($submission)->field_response;
                return $field;
            })->values();
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
        //Log::info('All Request:' . PHP_EOL . print_r($request->all(), true));
        try {
            $fieldsInput = $request->input('fields',[]);
            if (is_string($fieldsInput)) {
                $fieldsInput = json_decode($fieldsInput, true);
            }
            //Log::info('Request Input:' . PHP_EOL . print_r($fieldsInput, true));
            $fieldValues = collect($fieldsInput)->mapWithKeys(function ($item) {
                return [$item['field_id'] => $item['value']];
            });
            $fieldIds = $fieldValues->keys()->toArray();
            $formFields = FormField::whereIn('id', $fieldIds)->get();
            $validationRules = $customAttributes = $transformedInput =$fileType= [];
            foreach ($formFields as $field) {
                $fieldId = $field->id;
                $fieldName = $field->name;
                $value = $fieldValues->get($fieldId);
                $rules = is_array($field->validation_rule) ? $field->validation_rule : explode('|', $field->validation_rule);
                $validationRules[$fieldName] = implode('|', $rules);
                $customAttributes[$fieldName] = $field->label ?? "Field {$fieldId}";
                if ($value === $fieldName) {
                    Log::warning("Field ID: {$fieldId} has a value equal to its name. Value: {$value}");
                    $value = null;
                }
                if($field->type=='file')
                {
                    $fileType[]=$fieldId;
                }
                $transformedInput[$fieldName] = $value;
                //Log::info("Field ID: {$fieldId}, Label: {$field->label}, Value: " . print_r($value, true));
            }
            $messages = config('validationmessages') ?? [];
            $validator = Validator::make($transformedInput, $validationRules, $messages, $customAttributes);
            // $validator->sometimes(['rent_month', 'rent_area_space'], 'numeric', function ($transformedInput) {
            //     return isset($transformedInput['rented_owned']) && $transformedInput['rented_owned'] === 'Rented';
            // });
            Log::info('Input Request:' . PHP_EOL . print_r($transformedInput, true));
            Log::info('Validation Rule:' . PHP_EOL . print_r($validationRules, true));
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            try {
                $results = [];
                foreach ($fieldsInput as $field) {
                    $fieldValue = $field['value'];
                    if (in_array($field['field_id'], $fileType)) {
                        Log::info('Image Data:' . PHP_EOL . print_r($field['value'], true));
                        $fieldId = $field['field_id'];
                        $fileKey = $field['value']['path'] ?? null;
                        Log::info("Processing file key: " . print_r($fileKey, true));
                        $filePath = null;
                        $existingRecord = FormSubmission::where([
                            'ngo_unique_id' => $request->ngo_unique_id,
                            'acknowledgement_number' => $request->acknowledgement_number,
                            'form_id' => $request->form_id,
                            'scheme_id' => $request->scheme_id,
                            'steps' => $request->steps,
                            'field_id' => $fieldId,
                            'user_id' => '120',
                        ])->first();
                        if ($existingRecord) {
                            $existingResponse = json_decode($existingRecord->field_response, true);
                            if (isset($existingResponse['path'])) {
                                $oldFilePath = public_path($existingResponse['path']);
                                if (file_exists($oldFilePath)) {
                                    unlink($oldFilePath);
                                    Log::info("Deleted old image: " . $oldFilePath);
                                }
                            }
                        }
                        if ($fileKey && $request->hasFile($fileKey)) {
                            $file = $request->file($fileKey);
                            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $destinationPath = public_path('uploads/gallery');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            $file->move($destinationPath, $filename);
                            $filePath = 'uploads/gallery/' . $filename;
                            $uploadedFiles[] = [
                                'field_id' => $fieldId,
                                'file_path' => $filePath,
                            ];
                        }
                        if ($filePath) {
                            $fieldValue['path'] = $filePath;
                        }
                    }
                    $field_response = is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue;
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
                $affectedRows = PmuIrProposalList::where('acknowledgement_number', $request->acknowledgement_number)->update(['status' => $status, 'status_changed_by' => JWTAuth::parseToken()->authenticate()->id, 'status_updated_at' => now()]);
                if ($status === 2 && $affectedRows > 0) {
                    $inspection = InspectionDetail::create([
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
                        // $token= $this->Login();
                        // if($token)
                        // {
                        //     $response=$this->sendStatus($token,$inspection);
                        //     if (isset($response['authorization']) && $response['authorization'] === 'Success') {
                        //         $updated=InspectionDetail::where('inspection_id', $inspection->inspection_id)->update(['pfrId' => $response['statusupdateresp']['pfrId']]);
                        //         if ($updated) {
                        //             Log::info('Pfr Id successfully updated.', ['pfrId' => $response['statusupdateresp']['pfrId']]);
                        //         } else {
                        //             Log::info('No rows were updated.', [
                        //                 'inspection_id' => $inspection->inspection_id,
                        //                 'pfrId' => $response['statusupdateresp']['pfrId']
                        //             ]);
                        //         }
                        //     } else {
                        //         Log::warning('Status update failed.', ['response' => $response]);
                        //     }
                        // }else{
                        //     Log::error('Invalid Credentials: Authentication failed for user.');
                        // } 
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
                    'line'  => $e->getLine(),
                ]);
                return response()->json([
                    'success' => false,
                    'line'  => $e->getLine(),
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
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
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
    }
    private function generateInspectionId($request)
    {
        $results = InspectionDetail::where(['acknowledgement_number' => $request->acknowledgement_number])->get();
        if ($results->count() > 0) {
            return (int) $results->last()->inspection_id + 1;
        } else {
            $parts = explode('/', ($request->acknowledgement_number));
            $lastPart = end($parts) . '1';
            return (int) $lastPart;
        }
    }
    public function stepsCheck(Request $request)
    {
       // return response()->json('sdfdsfdsf');
        // $token= $this->Login();
        // //echo $token;
        // //return response()->json($token,200);
        // if($token)
        // {
        //     $this->sendStatus($token,$inspection);
        // }else{
        //   return response()->json("Invalid Credentials",401);
        // }
    }
    private function Login()
    {
        $client = new Client();
        $data = [
            'username' => 'ngosje.pmuirinspection',
            'password' => 'U6@Ngo%sN6^'
        ];

        try {
            $response = $client->post($this->eanudannUrl . '/auth', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            $responseBody = $response->getBody()->getContents();
            $result = json_decode($responseBody, true);
            if (isset($result['authorization']) && $result['authorization'] === 'success') {
                return $result['token'];
            } else {
                return false;
            }
        } catch (RequestException $e) {
            return $e;
        }
    }
    private function sendStatus($token = null, $inspection)
    {
        $inspection = InspectionDetail::where('acknowledgement_number', $inspection->acknowledgement_number)->with(['proposal:acknowledgement_number,scheme_project_name,process_id,form_type,financial_year'])->first();
        $postData = [
            'schemeName' => $inspection->proposal->form_type ?? null,
            'appCode'    => '5',
            'apiKey'     => 'U6@nG0&uM7U4@Nz8^eN0',
            'pfrData'    => [
                'pfrInspectionId' => (string) $inspection->inspection_id,
                'ispfrDrafted'    => $inspection->status == 2 ? 0 : 1,
                'boUserId'        => $inspection->created_by,
                'ackNumber'       => $inspection->acknowledgement_number,
                'boProcessId'     => ($inspection->proposal->process_id ?? ''),
                'filledDate'      => Carbon::parse($inspection->created_at)->format('Y-m-d'),
                'financialYear'   => $inspection->proposal->financial_year ?? null,
            ]
        ];
        ///return response()->json($postData, 200);
        $client = new Client();
        try {
            $response = $client->post($this->eanudannUrl . '/ir/statusupdate', [
                'headers' => [
                    'Content-Type'  => 'application/json',  
                    ...(isset($token) ? ['Authorization' => "Bearer $token"] : []),
                ],
                'json' => $postData,
            ]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Status update failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return [
                'authorization' => 'Fail',
                'error' => $e->getMessage()
            ];
        }
    }
    // public function proposalInspection(Request $request)
    // {
    //     $providedKey = $request->header('X-Inspection-Secret');
    //     $expectedKey = env('INSPECTION_SECRET_KEY');

    //     if ($providedKey !== $expectedKey) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unauthorized: Invalid secret key.',
    //         ], 403);
    //     }
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //         'password' => 'required|string|min:6',
    //     ]);
    //     $credentials = $request->only('email', 'password');
    //     if (!$token = JWTAuth::attempt($credentials)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid Email or Password',
    //         ], 401);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login successful via Email',
    //         'token' => $token
    //     ]);
    // }
    // public function proposalInspectionReport(Request $request)
    // {
    //     $request->validate([
    //         'acknowledgement_number' => 'required|string|exists:inspection_details,acknowledgement_number',
    //     ]);
    //     $inspectionData=InspectionDetail::where('acknowledgement_number',$request->acknowledgement_number)->get();
    //     if ($inspectionData->isEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No inspection details found for the given acknowledgement number.'
    //         ], 404);
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'data' => $inspectionData
    //     ],200);
    // }
    public function proposalInspectionData(Request $request)
    {
        try {
            $submissions = FormSubmission::select(
                'form_submissions.acknowledgement_number',
                'form_formfield.column_name',
                'form_submissions.field_response'
            )
            ->leftJoin('form_formfield', function ($join) {
                $join->on('form_submissions.field_id', '=', 'form_formfield.formfield_id')
                    ->on('form_submissions.form_id', '=', 'form_formfield.form_id');
            })
            ->join('inspection_details', 'form_submissions.inspection_id', '=', 'inspection_details.inspection_id')
            ->where('inspection_details.pfrId', $request->pfr_id)
            ->get()
            ->groupBy('acknowledgement_number');

            if ($submissions->isEmpty()) {
                return response()->json([
                    'data' => null,
                    'message' => 'No inspection found for the given PFR ID.'
                ], 404);
            }

            $firstAckNumber = $submissions->keys()->first();
            $firstGroup = $submissions->first()->map(function ($item) {
                $decoded = json_decode($item->field_response, true);
                return [
                    'column_name'    => $item->column_name,
                    'field_response' => is_array($decoded) ? $decoded : $item->field_response,
                ];
            });

            return response()->json([
                'data' => [
                    'acknowledgement_number' => $firstAckNumber,
                    'data' => $firstGroup,
                ],
                'message' => 'Data retrieved successfully.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to get form submissions data: ' . $e->getMessage());
            return response()->json([
                'data' => null,
                'message' => 'Failed to retrieve Inspection Data.'
            ], 500);
        }

    }
}
