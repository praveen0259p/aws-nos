<?php

namespace App\Http\Controllers;

use App\Models\ApplicationWindow;
use App\Models\Application;
use App\Models\PersonalInfo;
use App\Models\ApplicationHistory;
use App\Models\Country;
use App\Models\University;
use App\Models\ForeignDetail;
use App\Models\EmploymentDetail;

use App\Models\VisaDetail;
use App\Models\Sibling;
use App\Models\FamilyMember;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\UploadAssetTrait;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    use UploadAssetTrait;
    public function applicationForm(Request $request)
    {
        $universities = University::where(['active' => 1])->pluck('institute_name', 'id');
        $countries = Country::where(['active' => 1])->pluck('name', 'code');
        $application = Application::with(['personalInfo', 'foreignDetail', 
        'employmentDetail','visaDetail','visaDetail.siblings','visaDetail.familyMembers'])
        ->Where(['user_id' => Auth::id(), 'window_id' => $request->get('active_window')->id])->latest()->first();
        session(['active_tab' => getApplicationTab($application->steps)]);
        //dd($application);
        return view('backend.application-form', compact('countries', 'universities', 'application'));
    }
    public function savepersonal(Request $request)
    {
        //dd($request->all());
        $window = $request->get('active_window');
        $userId = Auth::id();
        $windowId = $window->id;
        $validator = Validator::make($request->all(), [
            'applicant_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'dob' => ['required', 'date'],
            'mobile_no' => ['required', 'digits:10'],
            'email' => ['required', 'email'],
            'state' => ['required', 'exists:states,StateCode'],
            'district' => ['required', 'exists:districts,DistrictCode'],
            'board' => ['required', 'string', 'max:255'],
            'certificate_no' => ['required', 'string', 'max:255'],
            'year_of_passing' => ['required', 'digits:4', 'integer', 'between:1901,' . date('Y')],
            'marital_status' => ['required', 'in:' . implode(',', array_keys(marital_status()))],
            'aadhar' => ['nullable', 'digits:12', 'required_without:aadhar_enrollment'],
            'aadhar_enrollment' => ['nullable', 'required_without:aadhar'],
            'current_address_line1' => ['required', 'string'],
            'current_address_line2' => ['required', 'string'],
            'current_address_state' => ['required', 'exists:states,StateCode'],
            'current_address_district' => ['required', 'exists:districts,DistrictCode'],
            'current_address_pincode' => ['required', 'digits:6'],
            'permanent_address_line1' => ['required', 'string'],
            'permanent_address_line2' => ['required', 'string'],
            'permanent_address_state' => ['required', 'exists:states,StateCode'],
            'permanent_address_district' => ['required', 'exists:districts,DistrictCode'],
            'permanent_address_pincode' => ['required', 'digits:6'],
            'emergency_contact_person_name' => ['required', 'string'],
            'emergency_person_address' => ['required', 'string'],
            'emergency_person_contact_number' => ['required', 'digits:10'],
            'emergency_person_contact_email' => ['required', 'email'],
            'relationship_applicant' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('active_tab', 'pills-personal-tab');
        }
        $validatedData = $validator->validated();
        if ($window->isSubmissionOpen()) {
            $application = Application::firstOrNew([
                'user_id' => $userId,
                'window_id' => $windowId,
            ]);
            if (!$application->exists) {
                $application->application_number = generateApplicationNo($window);
                $application->application_start_date = Carbon::today();
            }
            $application->application_status = 0;
            $application->steps = 1;
            $application->save();
            PersonalInfo::updateOrCreate(
                ['application_id' => $application->id],
                [
                    'applicant_name' => $validatedData['applicant_name'],
                    'father_name' => $validatedData['father_name'],
                    'gender' => $validatedData['gender'],
                    'dob' => $validatedData['dob'],
                    'mobile_no' => $validatedData['mobile_no'],
                    'email' => $validatedData['email'],
                    'state' => $validatedData['state'],
                    'district' => $validatedData['district'],
                    'board' => $validatedData['board'],
                    'certificate_no' => $validatedData['certificate_no'],
                    'year_of_passing' => $validatedData['year_of_passing'],
                    'marital_status' => $validatedData['marital_status'],
                    'aadhar' => $validatedData['aadhar'] ?? null,
                    'aadhar_enrollment' => $validatedData['aadhar_enrollment'] ?? null,
                    'current_address_line1' => $validatedData['current_address_line1'],
                    'current_address_line2' => $validatedData['current_address_line2'],
                    'current_address_state' => $validatedData['current_address_state'],
                    'current_address_district' => $validatedData['current_address_district'],
                    'current_address_pincode' => $validatedData['current_address_pincode'],
                    'permanent_address_line1' => $validatedData['permanent_address_line1'],
                    'permanent_address_line2' => $validatedData['permanent_address_line2'],
                    'permanent_address_state' => $validatedData['permanent_address_state'],
                    'permanent_address_district' => $validatedData['permanent_address_district'],
                    'permanent_address_pincode' => $validatedData['permanent_address_pincode'],
                    'emergency_contact_person_name' => $validatedData['emergency_contact_person_name'],
                    'emergency_person_address' => $validatedData['emergency_person_address'],
                    'emergency_person_contact_number' => $validatedData['emergency_person_contact_number'],
                    'emergency_person_contact_email' => $validatedData['emergency_person_contact_email'],
                    'relationship_applicant' => $validatedData['relationship_applicant'],
                ]
            );
            //return redirect()->route('application-form.create')->with('success', 'Personal Info Saved successfully!');
            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Personal info saved successfully.');
        }
        if ($window->isEditOpen()) {
            //dd($request->all());
            $application = Application::where(['user_id'   => $userId, 'window_id' => $windowId])->firstOrFail();
            $personalInfo = PersonalInfo::where('application_id', $application->id)->firstOrFail();
            $personalInfo->fill($validatedData);
            if ($personalInfo->isDirty()) {
                $dirty    = $personalInfo->getDirty();
                $original = $personalInfo->getOriginal();
                $personalInfo->save();
                foreach ($dirty as $field => $newValue) {
                    ApplicationHistory::create([
                        'application_id'     => $application->id,
                        'user_id'            => $userId,
                        'window_id'          => $windowId,
                        'application_number' => $application->application_number,
                        'field_name'         => $field,
                        'old_value'          => $original[$field] ?? null,
                        'new_value'          => $newValue,
                    ]);
                }
                //return redirect()->route('application-form.create')->with('success', 'Personal Info updated successfully!');
                return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Personal info updated successfully.');
            } else {
                //return redirect()->route('application-form.create')->with('info', 'No changes were made.');
                return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('info', 'No changes were made.');
            }
        }
    }
    public function saveforeign(Request $request)
    {
        //dd($request->all());
        $max = $request->scoring_system == 1 ? 10 : 100;
        $validator = Validator::make($request->all(), [
            'degree_course'                  => 'required|string|max:255',
            'study_field'                  =>  'required|string|max:255',
            'research_title'               => 'required|string|max:255',
            'description'                  => 'required|string',

            'application_date'             => 'required|date',
            'anticipated_joining_date'     => 'required|date|after_or_equal:application_date',
            'anticipated_course_end_date'  => 'required|date|after:anticipated_joining_date',

            'university'                   => 'required|exists:universities,id',
            'country'                      => 'required|exists:countries,code',
            'course'                       => 'required|string|max:255',

            'college_name'                 => 'required|string|max:255',
            'course_state'                 => 'required|string|max:255',
            'course_district'              => 'required|string|max:255',
            'college_address'              => 'required|string',

            'course_taken'                 => 'required|string|max:255',
            'passing_year'                 => 'required|date',
            'scoring_system'               => 'required|in:1,2',
            'marks' => "required|numeric|min:0|max:$max",
            'research_detail_paper'        => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('active_tab', 'pills-foreign-tab');
        }
        $validatedData = $validator->validated();
        //dd($request->all());
        $window = $request->get('active_window');
        $application = Application::Where(['user_id' => Auth::id(), 'window_id' => $request->get('active_window')->id])->latest()->first();
        $validated['application_id'] = $application->id;
        if ($window->isSubmissionOpen()) {
            ForeignDetail::updateOrCreate(
                ['application_id' => $application->id],
                $validated
            );
            $application = Application::firstOrNew([
                'user_id' => Auth::id(),
                'window_id' => $request->get('active_window')->id,
            ]);
            $application->steps = 2;
            $application->save();
            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Foreign details saved successfully!');
        }
        if ($window->isEditOpen()) {
            $foreignDetail = ForeignDetail::where('application_id', $application->id)->firstOrFail();
            $foreignDetail->fill($validated);
            if ($foreignDetail->isDirty()) {
                $dirty = $foreignDetail->getDirty();
                $original = $foreignDetail->getOriginal();
                $foreignDetail->save();
                foreach ($dirty as $field => $newValue) {
                    ApplicationHistory::create([
                        'application_id' => $application->id,
                        'user_id' => Auth::id(),
                        'window_id' => $window->id,
                        'application_number' => $application->application_number,
                        'field_name' => $field,
                        'old_value' => $original[$field] ?? null,
                        'new_value' => $newValue,
                    ]);
                }
                return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Foreign details updated successfully!');
                //return redirect()->route('foreign')->with('success', 'Foreign details updated successfully!');
            }
            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('info', 'No changes were made.');
            //return redirect()->route('foreign')->with('info', 'No changes were made.');
        }
    }
    public function saveemployment(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'currentlyEmployed' => 'required|in:yes,no',
            'current_job_nature' => 'nullable|required_if:currentlyEmployed,yes|string|max:255',
            'current_office_name' => 'nullable|required_if:currentlyEmployed,yes|string|max:255',
            'current_office_address' => 'nullable|required_if:currentlyEmployed,yes|string|max:500',
            'current_office_state' => 'nullable|required_if:currentlyEmployed,yes|exists:states,StateCode',
            'current_office_district' => 'nullable|required_if:currentlyEmployed,yes|exists:districts,DistrictCode',
            'current_office_designation' => 'nullable|required_if:currentlyEmployed,yes|string|max:255',
            'current_annual_salary' => 'nullable|required_if:currentlyEmployed,yes|numeric|min:0',

            'employed_earlier' => 'required|in:yes,no',
            'employed_earlier_job_nature' => 'nullable|required_if:employed_earlier,yes|string|max:255',
            'employed_earlier_office' => 'nullable|required_if:employed_earlier,yes|string|max:255',
            'employed_earlier_office_address' => 'nullable|required_if:employed_earlier,yes|string|max:500',
            'employed_earlier_office_state' => 'nullable|required_if:employed_earlier,yes|exists:states,StateCode',
            'employed_earlier_office_district' => 'nullable|required_if:employed_earlier,yes|exists:districts,DistrictCode',
            'employed_earlier_office_designation' => 'nullable|required_if:employed_earlier,yes|string|max:255',
            'employed_earlier_salary' => 'nullable|required_if:employed_earlier,yes|numeric|min:0',

            'other_employment' => 'required|in:yes,no',
            'other_employment_job_nature' => 'nullable|required_if:other_employment,yes|string|max:255',
            'other_employment_job_office' => 'nullable|required_if:other_employment,yes|string|max:255',
            'other_employment_office_address' => 'nullable|required_if:other_employment,yes|string|max:500',
            'other_employment_office_state' => 'nullable|required_if:other_employment,yes|exists:states,StateCode',
            'other_employment_office_district' => 'nullable|required_if:other_employment,yes|exists:districts,DistrictCode',
            'other_employment_office_designation' => 'nullable|required_if:other_employment,yes|string|max:255',
            'other_employment_salary' => 'nullable|required_if:other_employment,yes|numeric|min:0',
            'other_employment_joining_date' => 'nullable|required_if:other_employment,yes|date|before_or_equal:today',
            'other_employment_leaving_date' => 'nullable|required_if:other_employment,yes|date|after_or_equal:other_employment_joining_date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('active_tab', 'pills-employment-tab');
        }
        $validated = $validator->validated();
        $window = $request->get('active_window');
        $application = Application::Where(['user_id' => Auth::id(), 'window_id' => $request->get('active_window')->id])->latest()->first();
        $validated['application_id'] = $application->id;
        if ($window->isSubmissionOpen()) {
            EmploymentDetail::updateOrCreate(
                ['application_id' => $application->id],
                $validated
            );
            $application = Application::firstOrNew([
                'user_id' => Auth::id(),
                'window_id' => $request->get('active_window')->id,
            ]);
            $application->steps = 3;
            $application->save();
            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Employment details saved successfully!');
        }
        if ($window->isEditOpen()) {
            $foreignDetail = EmploymentDetail::where('application_id', $application->id)->firstOrFail();
            $foreignDetail->fill($validated);
            if ($foreignDetail->isDirty()) {
                $dirty = $foreignDetail->getDirty();
                $original = $foreignDetail->getOriginal();
                $foreignDetail->save();
                foreach ($dirty as $field => $newValue) {
                    ApplicationHistory::create([
                        'application_id' => $application->id,
                        'user_id' => Auth::id(),
                        'window_id' => $window->id,
                        'application_number' => $application->application_number,
                        'field_name' => $field,
                        'old_value' => $original[$field] ?? null,
                        'new_value' => $newValue,
                    ]);
                }
                return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Employment details updated successfully!');
            }
            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('info', 'No changes were made.');
        }
    }
    public function savevisa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scholarshipSelect' => 'required|in:yes,no',
            'no_of_sibling_awarded' => 'nullable|required_if:scholarshipSelect,yes|integer|min:1|max:5',
            'siblings' => 'nullable|array|required_if:scholarshipSelect,yes',
            'siblings.*.name' => 'required|string|max:255',
            'siblings.*.relationship' => 'required|string|max:100',
            'siblings.*.year_of_award' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'siblings.*.course' => 'required|string|max:255',
            'visaAppliedSelect' => 'required|in:yes,no',
            'visaObtainedSelect' => 'required_if:visaAppliedSelect,yes|in:yes,no',
            'obtained_visa_type' => 'nullable|required_if:visaObtainedSelect,yes|string|max:100',
            'family' => 'required|array|min:1',
            'family.*.relationship' => 'required|string|max:100',
            'family.*.name' => 'required|string|max:255',
            'family.*.age' => 'required|integer|min:0|max:120',
            'family.*.employment' => 'required|string|max:100',
            'family.*.income' => 'required|numeric|min:0',
            'family.*.itrstatus' => 'required|in:Filled,Not Filled',
        ]);
        //dd($validator->errors()->toArray());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('active_tab', 'pills-visa-tab');
        }
        $validated = $validator->validated();
        $window = $request->get('active_window');
        $application = Application::Where(['user_id' => Auth::id(), 'window_id' => $request->get('active_window')->id])->latest()->first();
        $validated['application_id'] = $application->id;
        if ($window->isSubmissionOpen()) {
            $application = Application::firstOrNew([
                'user_id'   => Auth::id(),
                'window_id' => $request->get('active_window')->id,
            ]);
            $application->steps = 4;
            $application->save();
            $visaData = [
                'scholarship_select'       => $validated['scholarshipSelect'],
                'no_of_sibling_awarded'    => $validated['no_of_sibling_awarded'] ?? null,
                'visa_applied_select'       => $validated['visaAppliedSelect'],
                'visa_obtained_select'      => $validated['visaObtainedSelect'] ?? null,
                'obtained_visa_type'        => $validated['obtained_visa_type'] ?? null,
            ];
            $visa = VisaDetail::updateOrCreate(
                ['application_id' => $application->id],
                $visaData
            );
            if (!empty($validated['siblings'])) {
                foreach ($validated['siblings'] as $sibling) {
                    $visa->siblings()->updateOrCreate(
                        [
                            'visa_detail_id' => $visa->id,
                            'name' => $sibling['name'],
                        ],
                        [
                            'name'          => $sibling['name'],
                            'relationship'  => $sibling['relationship'],
                            'year_of_award' => $sibling['year_of_award'],
                            'course'        => $sibling['course'],
                        ]
                    );
                }
            }
            if (!empty($validated['family'])) {
                foreach ($validated['family'] as $member) {
                    $visa->familyMembers()->updateOrCreate(
                        [
                            'visa_detail_id' => $visa->id,
                            'name' => $member['name'],
                        ],
                        [
                            'relationship' => $member['relationship'],
                            'name'         => $member['name'],
                            'age'          => $member['age'],
                            'employment'   => $member['employment'],
                            'income'       => $member['income'],
                            'itr_status'   => $member['itrstatus'],
                        ]
                    );
                }
            }

            return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)
                ->with('success', 'Visa details saved successfully!');
        }

        // if ($window->isEditOpen()) {
        //     $foreignDetail = EmploymentDetail::where('application_id', $application->id)->firstOrFail();
        //     $foreignDetail->fill($validated);
        //     if ($foreignDetail->isDirty()) {
        //         $dirty = $foreignDetail->getDirty();
        //         $original = $foreignDetail->getOriginal();
        //         $foreignDetail->save();
        //         foreach ($dirty as $field => $newValue) {
        //             ApplicationHistory::create([
        //                 'application_id' => $application->id,
        //                 'user_id' => Auth::id(),
        //                 'window_id' => $window->id,
        //                 'application_number' => $application->application_number,
        //                 'field_name' => $field,
        //                 'old_value' => $original[$field] ?? null,
        //                 'new_value' => $newValue,
        //             ]);
        //         }
        //         return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('success', 'Employment details updated successfully!');
        //     }
        //     return redirect()->route('application-form.create')->with('active_tab', $request->next_tab)->with('info', 'No changes were made.');
        // }
    }
    // // admin panel  functionality
    public function applications()
    {
        $applications = Application::with(['window', 'personalInfo', 'foreignDetail', 'history'])->get();
        return view('backend.applications.list', compact('applications'));
    }
}
