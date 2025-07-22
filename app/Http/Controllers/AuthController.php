<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\FormField;
use App\Models\PmuIrProposalList;
use App\Models\User;
use App\Models\Contact;
use App\Models\FormFieldOption;
use App\Models\FAQ;
use App\Models\Gallery;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    protected array $labels = [
        0 => 'Pending',
        1 => 'Draft',
        2 => 'Submitted'
    ];
    public function login(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:users,mobile_no',
        ],[
            'exists' => 'Invalid Mobile Number',
            'mobile_no.regex' => 'The mobile number must start with 6, 7, 8, or 9 and must be 10 digits long.',
        ]);
        try {
            self::sendOtp($request->mobile_no);
            return response()->json([
                'status' => true,
                'message' => 'OTP sent to mobile',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|regex:/^[6-9]\d{9}$/|exists:users,mobile_no',
            'otp' => 'required|digits:6'
        ],[
            'exists' => 'Invalid Mobile Number',
            'mobile_no.regex' => 'The mobile number must start with 6, 7, 8, or 9 and must be 10 digits long.',
        ]);
        $mobile = $request->mobile_no;
        $otp = $request->otp;
        $storedOtp = User::where('mobile_no', $mobile)->first();

        // if (now()->gt($storedOtp->otp_expires_at)) {
        //     return response()->json(['error' => 'OTP has expired. Please request a new one.'], 401);
        // }
        $storedOtp = $storedOtp->otp;
        if ($storedOtp && $storedOtp == $otp) {
            $user = User::where('mobile_no', $mobile)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $token = auth('api')->login($user);
            User::where('mobile_no', $mobile)->update([
                'otp' => null,
                'otp_expires_at' => null
            ]);
            return response()->json([
                'message' => 'OTP verified successfully',
                'token' => $token,
                'token_type' => 'bearer',
                //'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]);
        }
        return response()->json(['error' => 'Invalid OTP'], 401);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.'
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is already invalid or expired.'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not log out user.'
            ], 500);
        }
    }
    public function dashboard(Request $request)
    {
        //return response()->json($request->form_id, 200);
        // switch($request->form_id){
        //     case 5:
        //         return $this->inspectionReportDashboard();
        //         break;
        //     case 6:
        //         break;

        //     default:
        // }
        try {
            $statusLabels = $this->labels;
            $dashboard = array_fill_keys(array_keys($statusLabels), 0);
            $result = JWTAuth::parseToken()->authenticate()->district_id;
 
            if (!is_array($result) || empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No district IDs found for the user.'
                ], 422);
            }
            //return response()->json($result, 200);
            foreach ($result as $data) {
                $rawCounts = PmuIrProposalList::where('Lgd_State_Code', $data['lgd_state_code'])->where('Lgd_District_Code', $data['lgd_district_code'])
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                foreach ($statusLabels as $status => $label) {
                    $dashboard[$status] += $rawCounts[$status] ?? 0;
                }
            }
            $formatted = [];
            foreach ($dashboard as $status => $count) {
                $formatted[] = [
                    'name' => $statusLabels[$status],
                    'count' => $count,
                    'status' => $status
                ];
            }
            return response()->json([
                'success' => true,
                'data' => $formatted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard status counts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function GetProposalListShrest(Request $request)
    {
        $stepsArray = FormField::select('steps')->where([ 'scheme_id'=>5,'form_id'=>5,'active'=>1])->distinct()->pluck('steps');
        $totalSteps=$stepsArray->toArray();
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys($this->labels)),
        ]);
        try {
            $proposals = [];
            $user = JWTAuth::parseToken()->authenticate();
            $districtCodes = JWTAuth::parseToken()->authenticate()->district_id;
            $allProposals = collect();
            foreach($districtCodes as $data) {
                $query = PmuIrProposalList::query()
                ->with([
                    'formSubmissions' => function ($q) {
                        $q->select('id', 'project_id', 'field_id');
                    },
                    'formSubmissions.field:id,steps'
                ])
                ->where('status', $request->status)
                ->where('Lgd_State_Code', $data['lgd_state_code'])->where('Lgd_District_Code', $data['lgd_district_code'])
                ->get();
                $allProposals = $allProposals->merge($query);
            }
            return response()->json([
            'status' => 200,
            'count'=> $allProposals->count(),
            'proposals' => $allProposals->map(function ($proposal) use ($totalSteps) {
                $maxStepValue = $proposal->formSubmissions
                    ->map(fn($sub) => optional($sub->field)->steps)
                    ->filter()
                    ->max();
                $maxStep = $maxStepValue !== null ? $maxStepValue + 1 : 1;
                $totalStep = !empty($totalSteps) ? max($totalSteps) : 0;
                return array_merge(
                    $proposal->makeHidden('formSubmissions')->toArray(),
                    [
                        'max_step' => $maxStep,
                        'completedStep' => $maxStepValue,
                        'totalStep' => $totalStep,
                    ]
                );
            }),
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private static function sendOtp($mobile)
    {
        $otp = 123456;
        User::where('mobile_no', $mobile)->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);
        $apiUrl = 'https://pmajay.dosje.gov.in/api/send/sms';
        $postData = [
            'templet' => 'otp',
            'mobile' => $mobile,
            'param[otp]' => $otp
        ];
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        return response()->json(['message' => 'OTP sent successfully'], 200);
    }
    public function schemes()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $schemeIdsString = $user->scheme_id;
            $schemeIdsArray = explode(',', $schemeIdsString);
            $schemes = Scheme::with('forms')->whereIn('id', $schemeIdsArray)->get();
            return response()->json([
                'success' => true,
                'schemes' => $schemes
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function menus(Request $request)
    {
        try {
            $menus=config('menu');
            $schemeMenus = $menus[$request->scheme_id] ?? [];
            if (!is_array($menus)) {
                throw new \Exception('Menu configuration is invalid.');
            }
            return response()->json($schemeMenus, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {

        try {
            return response()->json([
                'status' => 200,
                'data' => JWTAuth::parseToken()->authenticate()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function inspectionReportDashboard()
    {
        try {
            $statusLabels = $this->labels;
            $dashboard = array_fill_keys(array_keys($statusLabels), 0);
            $result = JWTAuth::parseToken()->authenticate()->district_id;

            if (!is_array($result) || empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No district IDs found for the user.'
                ], 422);
            }
            //return response()->json($result, 200);
            foreach ($result as $data) {
                $rawCounts = PmuIrProposalList::where('Lgd_State_Code', $data['lgd_state_code'])->where('Lgd_District_Code', $data['lgd_district_code'])
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                foreach ($statusLabels as $status => $label) {
                    $dashboard[$status] += $rawCounts[$status] ?? 0;
                }
            }
            $formatted = [];
            foreach ($dashboard as $status => $count) {
                $formatted[] = [
                    'name' => $statusLabels[$status],
                    'count' => $count,
                    'status' => $status
                ];
            }
            return response()->json([
                'success' => true,
                'data' => $formatted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard status counts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function FAQ(Request $request)
    {

        try {
            $faq =  FAQ::query()->where('status', '1')->get();
            return response()->json([
                'status' => 200,
                'data' => $faq
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function Contacts(Request $request)
    {
        try {
            $contact =   Contact::all();
            return response()->json([
                'status' => 200,
                'data' => $contact,

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function StateFilter(Request $request)
    {
        try {
            $districtIds =  JWTAuth::parseToken()->authenticate()->district_id;
            $options = FormFieldOption::find(3);
            $filtered = [];
            $addedStateCodes = [];
            foreach ($districtIds as $district) {
                foreach ($options->values as $option) {
                    if (
                        $option['StateCode'] == $district['lgd_state_code'] &&
                        !in_array($option['StateCode'], $addedStateCodes)
                    ) {
                        $filtered[] = [
                            'state'     => $option['StateCode'],
                            'statename' => $option['StateName']
                        ];
                        $addedStateCodes[] = $option['StateCode'];
                        break;
                    }
                }
            }
            usort($filtered, function($a, $b) {
                return $a['statename'] <=> $b['statename'];
            });
            return response()->json($filtered, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function DistrictFilter(Request $request)
    {
        $stateCode = $request->state;
        try {
            $userDistrictIds = JWTAuth::parseToken()->authenticate()->district_id;
            $options = FormFieldOption::find(4);
            $filtered = [];
            foreach ($userDistrictIds as $district) {
                foreach ($options->values as $option) {

                    if (($option['DistrictCode'] == $district['lgd_district_code']) &&
                        (!$stateCode || $option['StateCode'] == $stateCode)
                    ) {

                        $filtered[] = [
                            'district'     => $option['DistrictCode'],
                            'districtname' => $option['DistrictName'],
                            'statecode'    => $option['StateCode']
                        ];
                    }
                }
            }
            usort($filtered, function($a, $b) {
                return $a['districtname'] <=> $b['districtname'];
            });
            return response()->json($filtered, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function preview(Request $request)
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $html = view('preview')->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="preview.pdf"');
    }
    public function photo(Request $request)
    {
        $savedImages = [];
        try {
            $imageDataArray = json_decode($request->input('image'), true);
            if (!is_array($imageDataArray)) {
                return response()->json(['error' => 'Invalid image data'], 400);
            }
            foreach ($imageDataArray as $index => $imageData) {
                $image = new Gallery();
                if ($request->hasFile("files.$index")) {
                    $file = $request->file("files.$index");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('uploads/gallery');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $file->move($destinationPath, $filename);
                    $image->filename = 'uploads/gallery/' . $filename;
                } else {
                    $image->filename = null;
                }
                $image->project_id = $imageData['project_id'];
                $image->type = $imageData['name'];
                $image->latitude = $imageData['latitude'];
                $image->longitude = $imageData['longitude'];
                $image->save();
                $savedImages[] = $image;
            }
            return response()->json([
                'message' => 'Images uploaded successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error uploading images: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to upload images.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function submissionData(Request $request){
        $request->validate([
            'project_id'=> 'required|exists:pmu_ir_proposal_lists,project_id',
        ]);
        try {
            $submissions = FormSubmission::select('form_fields.label as field_label', 'form_submissions.field_response')
            ->join('form_fields', 'form_submissions.field_id', '=', 'form_fields.id')
            ->where('form_submissions.project_id', $request->project_id) 
            ->get();

            return response()->json([
                'data'=> $submissions,
                'message' => 'Data Fetched Successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to get form submissions data: ' . $e->getMessage());
            return response()->json([
                'data'=> null,
                'message' => 'Failed to retrieve form submissions Data.'
            ], 500);
        }
    }
}
