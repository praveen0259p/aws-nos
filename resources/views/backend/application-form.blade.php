@extends('backend.layouts.app')
@section('title', 'Application Form')
@section('content')
@php $activeTab = session('active_tab', 'pills-personal-tab'); @endphp
<div class="container-fluid">
   <div class="row py-2">
      <div class="col-xl-12 col-12">
         <h1>National Overseas Scholarship Scheme 2025-26</h1>
      </div>
   </div>
   @if(session('info'))
   <div class="alert alert-info">
      {{ session('info') }}
   </div>
   @endif
   @if(session('success'))
   <div class="alert alert-success">
      {{ session('success') }}
   </div>
   @endif
   <div class="row py-2">
      <div class="col-12">
         <ul class="nav nav-pills mb-3 stepper-form" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-personal-tab' ? 'active' : '' }}" id="pills-personal-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-personal" type="button" role="tab" aria-controls="pills-personal"
                  aria-selected="true"><span class="circle"><span class="step-number">1</span> <span
                        class="check d-none">✔</span></span> Personal Info</button>
            </li>
            <li class="nav-item"><span class="connector"></span></li>
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-foreign-tab' ? 'active' : '' }}" id="pills-foreign-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-foreign" type="button" role="tab" aria-controls="pills-foreign"
                  aria-selected="false"><span class="circle"><span class="step-number">2</span> <span
                        class="check d-none">✔</span></span> Foreign University</button>
            </li>
            <li><span class="connector"></span></li>
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-employment-tab' ? 'active' : '' }}" id="pills-employment-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-employment" type="button" role="tab" aria-controls="pills-employment"
                  aria-selected="false"><span class="circle"><span class="step-number">3</span> <span
                        class="check d-none">✔</span></span>Employment / Gap</button>
            </li>
            <li><span class="connector"></span></li>
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-visa-tab' ? 'active' : '' }}" id="pills-visa-tab" data-bs-toggle="pill" data-bs-target="#pills-visa"
                  type="button" role="tab" aria-controls="pills-visa" aria-selected="false"><span
                     class="circle"><span class="step-number">4</span> <span class="check d-none">✔</span></span>
                  Visa / Income</button>
            </li>
            <li><span class="connector"></span></li>
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-documents-tab' ? 'active' : '' }}" id="pills-documents-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-documents" type="button" role="tab" aria-controls="pills-documents"
                  aria-selected="false"><span class="circle"><span class="step-number">5</span> <span
                        class="check d-none">✔</span></span> Upload Docs</button>
            </li>
            <li><span class="connector"></span></li>
            <li class="nav-item" role="presentation">
               <button class="nav-link {{ $activeTab == 'pills-preview-tab' ? 'active' : '' }}" id="pills-preview-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-preview" type="button" role="tab" aria-controls="pills-preview"
                  aria-selected="false"><span class="circle"><span class="step-number">6</span> <span
                        class="check d-none">✔</span></span> Preview</button>
            </li>
         </ul>
         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade {{ $activeTab == 'pills-personal-tab' ? 'show active' : '' }}" id="pills-personal" role="tabpanel"
               aria-labelledby="pills-personal-tab" tabindex="0">
               <form id="personal-details" action="{{route('personal.save')}}" method="post"
                  class="py-4 form-box">
                  @csrf
                  <div class="form-step">
                     <div class="row g-3">
                        <input type="hidden" name="next_tab" value="pills-foreign-tab">
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="applicant_name" type="text" value="{{Auth::user()->full_name}}"
                              placeholder="Applicant Name" label="Applicant Name" icon="bi-person"
                              autocomplete="applicant_name" :required="true" class="bg-input-disabled"
                              readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="father_name" type="text" value="{{Auth::user()->father_name}}"
                              placeholder="Father's Name" label="Father's Name" icon="bi-people"
                              autocomplete="father_name" :required="true" class="bg-input-disabled"
                              readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="gender" icon="bi-grid-fill" label="Select Gender"
                              :options="genderOptions()" placeholder="Choose Gender"
                              selected="{{Auth::user()->gender}}" class="select-readonly" readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="dob" type="date" placeholder="D.O.B" label="D.O.B"
                              icon="bi-calendar2-event" autocomplete="dob" :required="true"
                              value="{{Auth::user()->dob?->format('Y-m-d')}}" class="bg-input-disabled"
                              readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="mobile_no" type="number" value="{{Auth::user()->mobile}}"
                              placeholder="Mobile No." label="Mobile No." icon="bi-phone"
                              autocomplete="mobile_no" :required="true" class="bg-input-disabled" readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="email" type="email" value="{{Auth::user()->email}}"
                              placeholder="Email Id" label="Email Id" icon="bi-envelope" autocomplete="email"
                              :required="true" class="bg-input-disabled" readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="state" icon="bi-geo-alt" label="Domicile State"
                              :options="getAllState()" placeholder="Domicile State"
                              selected="{{Auth::user()->state}}" class="select-readonly" readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="district" icon="bi-buildings" label="Domicile District"
                              :options="getDistrictsByStateId(Auth::user()->state)"
                              placeholder="Domicile District" selected="{{Auth::user()->district}}"
                              class="select-readonly" readonly />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="board" type="text" value="{{$application->personalInfo->board ?? ''}}"
                              placeholder="Enter Your Name of Board"
                              label="Name of Board(10th/Highschool/Secondary)" icon="bi-person-check"
                              autocomplete="board" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="certificate_no" type="text"
                              value="{{$application->personalInfo->certificate_no ?? ''}}"
                              placeholder="Enter Your 10th Board Certificate Number"
                              label="10th Board Certificate Number" icon="bi-postcard"
                              autocomplete="certificate_no" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="year_of_passing" type="number"
                              value="{{$application->personalInfo->year_of_passing ?? ''}}" placeholder="Year of Passing"
                              label="Year of Passing" icon="bi-calendar4-week" autocomplete="year_of_passing"
                              :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="marital_status" icon="bi-postcard-heart"
                              selected="{{$application->personalInfo->marital_status ?? ''}}" label="Marital Status"
                              :options="marital_status()" placeholder="Select Marital Status" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="aadhar" type="number" value="{{$application->personalInfo->aadhar ?? ''}}"
                              placeholder="Enter the Aadhar Number" label="Aadhar Number"
                              icon="bi-card-heading" autocomplete="aadhar" :required="false" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="aadhar_enrollment" type="text"
                              value="{{$application->personalInfo->aadhar_enrollment ?? ''}}"
                              placeholder="Enter the Enrollment Id"
                              label="Aadhar Enrollment ID(In case Aadhaar is not available and applied)"
                              icon="bi-person-vcard" autocomplete="aadhar_enrollment" :required="false" />
                        </div>
                        <h3 class="fw-bold">Current Address</h3>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="current_address_line1" type="text"
                              value="{{$application->personalInfo->current_address_line1 ?? ''}}"
                              placeholder="Enter the Address Line 1" label="Address Line 1" icon="bi-pin-map"
                              data-current="current_address_line1" autocomplete="current_address_line1"
                              :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="current_address_line2" type="text"
                              value="{{$application->personalInfo->current_address_line2 ?? ''}}"
                              placeholder="Enter the Address Line 2" label="Address Line 2"
                              data-current="current_address_line2" icon="bi-pin-map"
                              autocomplete="current_address_line2" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="current_address_state" icon="bi-geo-alt" label="Select State"
                              selected="{{$application->personalInfo->current_address_state ?? null}}"
                              data-current="current_address_state" :options="getAllState()"
                              placeholder="Select State" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="current_address_district" icon="bi-grid-fill"
                              selected="{{$application->personalInfo->current_address_district ?? ''}}"
                              :options="getDistrictsByStateId($application->personalInfo->current_address_state ?? null)"
                              data-current="current_address_district" label="Select District"
                              placeholder="Select District" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="current_address_pincode" type="number"
                              value="{{$application->personalInfo->current_address_pincode ?? ''}}"
                              placeholder="Enter the PIN Code" label="PIN Code"
                              data-current="current_address_pincode" icon="bi-geo"
                              autocomplete="current_address_pincode" :required="true" />
                        </div>
                        <h3 class="fw-bold">Permanent Address</h3>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="permanent_address_line1" type="text"
                              value="{{$application->personalInfo->permanent_address_line1 ?? ''}}"
                              placeholder="Enter the Address Line 1" label="Address Line 1" icon="bi-pin-map"
                              autocomplete="permanent_address_line1" :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="permanent_address_line2" type="text"
                              value="{{$application->personalInfo->permanent_address_line2 ?? ''}}"
                              placeholder="Enter the Address Line 2" label="Address Line 2" icon="bi-pin-map"
                              data-permanent="permanent_address_line2" autocomplete="permanenet_address_line2"
                              :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="permanent_address_state" icon="bi-geo-alt"
                              selected="{{$application->personalInfo->permanent_address_state ?? null}}"
                              data-permanent="permanent_address_state" label="Select State"
                              :options="getAllState()" placeholder="Select State" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-select-input name="permanent_address_district" icon="bi-grid-fill"
                              label="Select District"
                              selected="{{$application->personalInfo->permanent_address_district ?? null}}"
                              :options="getDistrictsByStateId($application->personalInfo->permanent_address_state ?? null)"
                              data-permanent="permanent_address_districts" placeholder="Select District" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="permanent_address_pincode" type="number"
                              value="{{$application->personalInfo->permanent_address_pincode ?? ''}}"
                              placeholder="Enter the PIN Code" label="PIN Code" icon="bi-geo"
                              autocomplete="permanent_address_pincode" :required="true" />
                        </div>
                        <h3 class="fw-bold">Emergency Contact Details</h3>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="emergency_contact_person_name" type="text"
                              value="{{$application->personalInfo->emergency_contact_person_name ?? ''}}"
                              placeholder="Emergency Contact Person Name" label="Contact Person Name"
                              icon="bi-person" autocomplete="emergency_contact_person_name"
                              :required="true" />
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-12 mb-3">
                           <x-text-input name="emergency_person_address" type="text"
                              value="{{$application->personalInfo->emergency_person_address ?? ''}}"
                              placeholder="Enter the Address" label="Address" icon="bi-pin-map"
                              autocomplete="emergency_person_address" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="emergency_person_contact_number" type="number"
                              value="{{$application->personalInfo->emergency_person_contact_number ?? ''}}"
                              placeholder="Enter Contact No" label="Enter Contact No" icon="bi-phone"
                              autocomplete="emergency_person_contact_number" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="emergency_person_contact_email" type="email"
                              value="{{$application->personalInfo->emergency_person_contact_email ?? ''}}"
                              placeholder="Email Id" label="Email Id" icon="bi-envelope"
                              autocomplete="emergency_person_contact_email" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="relationship_applicant" type="text"
                              value="{{$application->personalInfo->relationship_applicant ?? ''}}"
                              placeholder="Enter the Relationship with Applicant"
                              label="Relationship with Applicant" icon="bi-people"
                              autocomplete="relationship_applicant" :required="true" />
                        </div>
                     </div>
                     <div class="mt-4 text-center">
                        <button type="submit" class="next-btn fw-bold">Save Next <i class="bi bi-arrow-right"
                              aria-hidden="true"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade {{ $activeTab == 'pills-foreign-tab' ? 'show active' : '' }}" id="pills-foreign" role="tabpanel" aria-labelledby="pills-foreign-tab"
               tabindex="0">
               <form id="foreign-details" action="{{route('foreign.save')}}" method="post" class="py-4 form-box">
                  @csrf
                  <div class="form-step">
                     <div class="row g-3">
                        <input type="hidden" name="next_tab" value="pills-employment-tab">
                        <div class="col-xxl-3 col-lg-6 col-12 mb-3">
                           <label for="study" class="form-label fw-bold mb-2">Degree Course Applied for {{$application->foreignDetail->degree_course}}<sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="radio-field rounded-1">
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="degree_course"
                                    id="masterdegree" value="masterdegree"
                                    {{ old('degree_course', $application->foreignDetail->degree_course ?? '') == 'masterdegree' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="masterdegree">Master's
                                    Degree</label>
                              </div>
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="degree_course" id="phd"
                                    value="phd" {{ old('degree_course', $application->foreignDetail->degree_course ?? '') == 'phd' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="phd">Ph.D.</label>
                              </div>
                           </div>
                           @error('degree_course')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="study_field"
                              value="{{$application->foreignDetail->study_field ?? ''}}" type="text" placeholder="Enter the Field of Study"
                              label="Field of Study" icon="bi-journal-text" autocomplete="study_field"
                              :required="true" />
                        </div>
                        <div class="col-xxl-5 col-lg-12 col-12 mb-3">
                           <x-text-input name="research_title" type="text"
                              value="{{$application->foreignDetail->research_title ?? ''}}"
                              placeholder="Enter the Research Title" label="Research Title"
                              icon="bi-journal-text" autocomplete="research_title" :required="true" />
                        </div>
                        <div class="col-md-12 col-12 mb-3">
                           <x-textarea label="Description" name="description"
                              :value="old('description', $application->foreignDetail->description ?? '')"
                              placeholder="Enter Your Description Here" rows="5" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="application_date" type="date"
                              value="{{$application->foreignDetail->application_date->format('Y-m-d') ?? ''}}"
                              placeholder="Application/Registration/Admission date"
                              label="Application/Registration/Admission date " icon="bi-calendar2-event"
                              autocomplete="application_date" :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="anticipated_joining_date" type="date"
                              value="{{$application->foreignDetail->anticipated_joining_date->format('Y-m-d') ?? ''}}"
                              placeholder="Anticipated Joining date" label="Anticipated Joining date"
                              icon="bi-calendar2-event" autocomplete="anticipated_joining_date"
                              :required="true" />
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <x-text-input name="anticipated_course_end_date" type="date"
                              value="{{$application->foreignDetail->anticipated_course_end_date->format('Y-m-d') ?? ''}}"
                              placeholder="Anticipiated Course End date" label="Anticipiated Course End date"
                              icon="bi-calendar2-event" autocomplete="anticipated_course_end_date"
                              :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-select-input name="university" icon="bi-hospital"
                              label="Name of Institute/University" :options="$universities"
                              selected="{{$application->foreignDetail->university ?? ''}}"
                              placeholder="Name of Institute/University" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-select-input name="country" icon="bi-globe" label="Country" :options="$countries"
                              selected="{{$application->foreignDetail->country ?? ''}}"
                              placeholder="Country" />
                        </div>
                        <h3 class="fw-bold">Qualifying Exam Detail</h3>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="course" type="text" placeholder="Enter the Course Name"
                              value="{{$application->foreignDetail->course ?? ''}}"
                              label="Course" icon="bi-journal-bookmark" autocomplete="course"
                              :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="college_name" type="text" placeholder="Enter the College Name"
                              value="{{$application->foreignDetail->college_name ?? ''}}"
                              label="Name of College" icon="bi-building" autocomplete="college_name"
                              :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-select-input name="course_state" icon="bi-geo-alt" label="Select State"
                              :options="getAllState()"
                              selected="{{$application->foreignDetail->course_state ?? ''}}"
                              placeholder="Select State" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-select-input name="course_district"
                              selected="{{$application->foreignDetail->course_district ?? null}}"
                              :options="getDistrictsByStateId($application->foreignDetail->course_state ?? null)"
                              icon="bi-grid-fill" label="Select District"
                              placeholder="Select District" />
                        </div>
                        <div class="col-md-12 col-12 mb-3">
                           <x-text-input name="college_address" type="text"
                              value="{{$application->foreignDetail->college_address ?? ''}}"
                              placeholder="Enter the College Address" label="College Address"
                              icon="bi-building" autocomplete="college_address" :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="course_taken" type="text"
                              value="{{$application->foreignDetail->course_taken ?? ''}}"
                              placeholder="Enter the Subject/Course taken" label="Subject/Course Taken"
                              icon="bi-journal-medical" autocomplete="course_taken" :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="passing_year" type="date"
                              value="{{$application->foreignDetail->passing_year->format('Y-m-d') ?? ''}}"
                              placeholder="Enter the Subject/Course taken" label="Year of Passing"
                              icon="bi-calendar2-week" autocomplete="passing_year" :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-select-input name="scoring_system" icon="bi-bar-chart" label="Scoring System"
                              :options="scoring_system()"
                              selected="{{$application->foreignDetail->scoring_system ?? ''}}"
                              placeholder="Scoring System" :required="true" />
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <x-text-input name="marks" type="text"
                              placeholder="Enter The Grading" label="Marks"
                              value="{{$application->foreignDetail->marks ?? ''}}"
                              icon="bi-journal-medical" autocomplete="marks" :required="true" />
                        </div>
                        <div class="col-md-12 col-12 mb-3">
                           <x-textarea label="Details of Published Research Papers"
                              :value="old('research_detail_paper', $application->foreignDetail->research_detail_paper ?? '')"
                              name="research_detail_paper" placeholder="Enter your details here" rows="5"
                              :required="true" />
                        </div>
                     </div>
                     <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="prev-btn fw-bold"><i class="bi bi-arrow-left"
                              aria-hidden="true"></i> Previous</button>
                        <button type="submit" class="next-btn fw-bold">Save Next <i class="bi bi-arrow-right"
                              aria-hidden="true"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade {{ $activeTab == 'pills-employment-tab' ? 'show active' : '' }}" id="pills-employment" role="tabpanel" aria-labelledby="pills-employment-tab"
               tabindex="0">
               <form id="employment-form" action="{{route('employment.save')}}" method="post" class="py-4 form-box">
                  @csrf
                  <div class="form-step">
                     <div class="row g-3">
                        <h3 class="fw-bold">Current Employement Detail</h3>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <input type="hidden" name="next_tab" value="pills-visa-tab">
                           <label for="study" class="form-label fw-bold mb-2">Currently Employed <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="radio-field rounded-1">
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="currentlyEmployed" id="currentlyEmployed" value="yes"
                                    {{ old('currentlyEmployed', $application->employmentDetail->currentlyEmployed ?? '') == 'yes' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="employedYes">Yes</label>
                              </div>
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="currentlyEmployed" id="employedno" value="no"
                                    {{ old('currentlyEmployed', $application->employmentDetail->currentlyEmployed ?? '') == 'no' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="employedno">No</label>
                              </div>
                           </div>
                           @error('currentlyEmployed')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div id="employment-fields" class="col-12">
                           <div class="row">
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="current_job_nature"
                                    value="{{$application->employmentDetail->current_job_nature ?? ''}}" type="text" placeholder="Enter the Nature of Job"
                                    label="Nature of Job" icon="bi-journal-text" autocomplete="current_job_nature"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="current_office_name"
                                    value="{{$application->employmentDetail->current_office_name ?? ''}}" type="text" placeholder="Enter Office name"
                                    label="Office Name" icon="bi-journal-text" autocomplete="current_office_name"
                                    :required="true" />
                              </div>
                              <div class="col-md-12 col-12 mb-3">
                                 <x-textarea label="Office Address" name="current_office_address"
                                    :value="old('current_office_address', $application->employmentDetail->current_office_address ?? '')"
                                    placeholder="Enter Your Description Here" rows="5" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="current_office_state" icon="bi-geo-alt"
                                    selected="{{$application->employmentDetail->current_office_state ?? null}}"
                                    data-permanent="current_office_state" label="Select State"
                                    :options="getAllState()" placeholder="Select State" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="current_office_district" icon="bi-grid-fill"
                                    selected="{{$application->employmentDetail->current_office_district ?? ''}}"
                                    :options="getDistrictsByStateId($application->employmentDetail->current_office_state ?? null)"
                                    label="Select District"
                                    placeholder="Select District" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="current_office_designation"
                                    value="{{$application->employmentDetail->current_office_designation ?? ''}}" type="text" placeholder="Enter Your Designation"
                                    label="Designation" icon="bi-journal-text" autocomplete="current_office_designation"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="current_annual_salary"
                                    value="{{$application->employmentDetail->current_annual_salary ?? ''}}" type="number" placeholder="Enter Your Current Salary"
                                    label="Current Salary(Annual)" icon="bi-journal-text" autocomplete="current_annual_salary"
                                    :required="true" />
                              </div>
                           </div>
                        </div>
                        <h3 class="fw-bold">Employed Earlier</h3>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="study" class="form-label fw-bold mb-2">Have you ever been employed
                              earlier? <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="radio-field rounded-1">
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="employed_earlier"
                                    id="employed_earlieryes" value="yes"
                                    {{ old('employed_earlier', $application->employmentDetail->employed_earlier ?? '') == 'yes' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="Yes">Yes</label>
                              </div>
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="employed_earlier"
                                    id="employed_earlierno" value="no"
                                    {{ old('employed_earlier', $application->employmentDetail->employed_earlier ?? '') == 'no' ? 'checked' : 'checked' }}>
                                 <label class="form-check-label fw-bold" for="no">No</label>
                              </div>
                           </div>
                           @error('employed_earlier')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div id="earlier-employment-fields" class="col-12">
                           <div class="row">
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="employed_earlier_job_nature" value="{{$application->employmentDetail->employed_earlier_job_nature ?? ''}}" type="text" placeholder="Enter the Nature of Job" label="Nature of Job" icon="bi-journal-text" autocomplete="employed_earlier_job_nature" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="employed_earlier_office" value="{{$application->employmentDetail->employed_earlier_office ?? ''}}" type="text" placeholder="Enter Office Name" label="Enter Office name" icon="bi-journal-text" autocomplete="employed_earlier_office" :required="true" />
                              </div>
                              <div class="col-md-12 col-12 mb-3">
                                 <x-text-input name="employed_earlier_office_address" value="{{$application->employmentDetail->employed_earlier_office_address ?? ''}}" type="text" placeholder="Enter Office Address" label="Office Address " icon="bi-journal-text" autocomplete="employed_earlier_office_address" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="employed_earlier_office_state" icon="bi-geo-alt" selected="{{$application->employmentDetail->employed_earlier_office_state ?? null}}" label="Select State" :options="getAllState()" placeholder="Select State" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="employed_earlier_office_district" icon="bi-grid-fill" selected="{{$application->employmentDetail->employed_earlier_office_district ?? ''}}" :options="getDistrictsByStateId($application->employmentDetail->employed_earlier_office_state ?? null)" label="Select District" placeholder="Select District" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="employed_earlier_office_designation" value="{{$application->employmentDetail->employed_earlier_office_designation ?? ''}}" type="text" placeholder="Enter Designation" label="Designation" icon="bi-journal-text" autocomplete="employed_earlier_office_designation" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="employed_earlier_salary" value="{{$application->employmentDetail->employed_earlier_salary ?? ''}}" type="number" placeholder="Enter Your Salary" label="Salary(Annual)" icon="bi-journal-text" autocomplete="employed_earlier_salary" :required="true" />
                              </div>
                           </div>
                        </div>

                        <h3 class="fw-bold">Other Employement</h3>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="study" class="form-label fw-bold mb-2">Anyother Employement Detail? <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="radio-field rounded-1">
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="other_employment"
                                    id="other_employmentyes" value="yes"
                                    {{ old('other_employment', $application->employmentDetail->other_employment ?? '') == 'yes' ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="Yes">Yes</label>
                              </div>
                              <div class="form-check form-check-inline mb-0">
                                 <input class="form-check-input" type="radio" name="other_employment"
                                    id="other_employmentno" value="no"
                                    {{ old('other_employment', $application->employmentDetail->other_employment ?? '') == 'no' ? 'checked' : 'checked' }}>
                                 <label class="form-check-label fw-bold" for="no">No</label>
                              </div>
                           </div>
                           @error('other_employment')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div id="other-employment-fields" class="col-12">
                           <div class="row">
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_job_nature"
                                    value="{{$application->employmentDetail->other_employment_job_nature ?? ''}}" type="text" placeholder="Enter the Nature of Job"
                                    label="Nature of Job" icon="bi-journal-text" autocomplete="other_employment_job_nature"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_job_office"
                                    value="{{$application->employmentDetail->other_employment_job_office ?? ''}}" type="text" placeholder="Enter Office name"
                                    label="Office Name" icon="bi-journal-text" autocomplete="other_employment_job_office"
                                    :required="true" />
                              </div>
                              <div class="col-md-12 col-12 mb-3">
                                 <x-text-input name="other_employment_office_address"
                                    value="{{$application->employmentDetail->other_employment_office_address ?? ''}}" type="text" placeholder="Enter Office Address"
                                    label="Office Address " icon="bi-journal-text" autocomplete="other_employment_office_address"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="other_employment_office_state" icon="bi-geo-alt"
                                    selected="{{$application->employmentDetail->other_employment_office_state ?? null}}"
                                    label="Select State"
                                    :options="getAllState()" placeholder="Select State" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="other_employment_office_district" icon="bi-grid-fill"
                                    selected="{{$application->employmentDetail->other_employment_office_district ?? ''}}"
                                    :options="getDistrictsByStateId($application->employmentDetail->other_employment_office_state ?? null)"
                                    label="Select District"
                                    placeholder="Select District" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_office_designation"
                                    value="{{$application->employmentDetail->other_employment_office_designation ?? ''}}" type="text" placeholder="Enter Designation"
                                    label="Designation" icon="bi-journal-text" autocomplete="other_employment_office_designation"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_salary"
                                    value="{{$application->employmentDetail->other_employment_salary ?? ''}}" type="number" placeholder="Enter Your Salary"
                                    label="Salary(Annual)" icon="bi-journal-text" autocomplete="other_employment_salary"
                                    :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_joining_date" type="date"
                                    value="{{ $application->employmentDetail?->other_employment_joining_date?->format('Y-m-d') }}"
                                    placeholder="Joining Date"
                                    label="Joining Date" icon="bi-calendar2-event"
                                    autocomplete="other_employment_joining_date" :required="true" />
                              </div>
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-text-input name="other_employment_leaving_date" type="date"
                                    value="{{$application->employmentDetail?->other_employment_leaving_date?->format('Y-m-d') }}"
                                    placeholder="Joining Date"
                                    label="Leaving Date" icon="bi-calendar2-event"
                                    autocomplete="other_employment_leaving_date" :required="true" />
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="prev-btn fw-bold"><i class="bi bi-arrow-left"
                              aria-hidden="true"></i> Previous</button>
                        <button type="submit" class="next-btn fw-bold">Save Next <i class="bi bi-arrow-right"
                              aria-hidden="true"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade {{ $activeTab == 'pills-visa-tab' ? 'show active' : '' }}" id="pills-visa" role="tabpanel" aria-labelledby="pills-visa-tab"
               tabindex="0">
               <form id="visa-form" action="{{route('visa.save')}}" method="post" class="py-4 form-box">
                  @csrf
                  <div class="form-step">
                     <h3 class="fw-bold">Award of National Overseas Scholarship Scheme Earlier</h3>
                     <div class="row g-3">
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <input type="hidden" name="next_tab" value="pills-documents-tab">
                           <label for="scholarshipSelect" class="form-label fw-bold mb-2">Have You/Any of your siblings was awarded this scholarship? <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1">
                              <span class="input-group-text bg-white text-blue" id="scholarship"><i class="bi bi-buildings" aria-hidden="true"></i></span>
                              <select class="form-select" name="scholarshipSelect" id="scholarshipSelect" aria-label="Select Office District" aria-describedby="scholarshipHelp">
                                 <option value="" {{ old('scholarshipSelect', $application->visaDetail->scholarship_select ?? '') == '' ? 'selected' : '' }}>Choose Option</option>
                                 <option value="no" {{ old('scholarshipSelect', $application->visaDetail->scholarship_select ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                 <option value="yes" {{ old('scholarshipSelect', $application->visaDetail->scholarship_select ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                              </select>
                           </div>
                           @error('scholarshipSelect')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3" id="scholarship-details-column">
                           <x-text-input name="no_of_sibling_awarded"
                              value="{{$application->visaDetail->no_of_sibling_awarded ?? ''}}" type="number" placeholder="Enter Number of Sibling's Awarded"
                              label="Number of Sibling's Awarded " icon="bi-journal-text" autocomplete="no_of_sibling_awarded"
                              :required="true" />
                        </div>
                        <div class="table-responsive" id="scholarship-details-table">
                           <table class="table table-bordered form-table">
                              <thead>
                                 <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Relationship with applicant</th>
                                    <th scope="col">Year of Award</th>
                                    <th scope="col">Course</th>
                                 </tr>
                              </thead>
                              <tbody id="scholarshipTableBody"></tbody>
                           </table>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="visaAppliedSelect" class="form-label fw-bold mb-2">Whether applied for Visa? <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1">
                              <span class="input-group-text bg-white text-blue" id="forvisa"><i class="bi bi-buildings" aria-hidden="true"></i></span>
                              @php
                                             $visaAppliedSelect = old(
                                             'visaAppliedSelect',
                                             optional($application->visaDetail->visa_applied_select)
                                             );
                                             @endphp
                              <select class="form-select" name="visaAppliedSelect" id="visaAppliedSelect" aria-label="Wheather applied for Visa?" aria-describedby="forvisaHelp">
                                 <option value="" selected>Choose Option</option>
                                 <option value="no" @selected($visaAppliedSelect==='no' )>No</option>
                                 <option value="yes" @selected($visaAppliedSelect==='yes' )>Yes</option>
                              </select>
                           </div>
                           @error('visaAppliedSelect')
                           <div class="text-danger mt-1">{{ $message }}</div>
                           @enderror
                        </div>
                        <div id="visa-details-section">
                           <div class="row">
                              <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <label for="visaObtained" class="form-label fw-bold mb-2">In case, Visa is applied for, whether it is obtained <sup class="text-danger" aria-hidden="true">*</sup></label>
                                 <div class="input-group rounded-1">
                                    @php
                                             $visaObtainedSelect = old(
                                             'visaObtainedSelect',
                                             optional($application->visaDetail->visa_obtained_select)
                                             );
                                             @endphp
                                    <span class="input-group-text bg-white text-blue"><i class="bi bi-calendar2-week" aria-hidden="true"></i></span>
                                    <select class="form-select" name="visaObtainedSelect" id="visaObtainedSelect" aria-label="Wheather applied for Visa?" aria-describedby="forvisaHelp" required>
                                       <option value="no" @selected($visaObtainedSelect==='no' )>No</option>
                                       <option value="yes" @selected($visaObtainedSelect==='yes' )>Yes</option>
                                    </select>
                                 </div>
                                 @error('visaObtainedSelect')
                                 <div class="text-danger mt-1">{{ $message }}</div>
                                 @enderror
                              </div>
                              <div id="visa-type-section" class="col-xxl-6 col-lg-6 col-12 mb-3">
                                 <x-select-input name="obtained_visa_type"
                                    icon="bi-geo-alt"
                                    selected="{{$application->employmentDetail->obtained_visa_type ?? null}}"
                                    label="Select Visa Type"
                                    :options="visa_types()" placeholder="Select Visa" :required="true" />
                              </div>
                           </div>
                        </div>
                        <h3 class="mb-1 fw-bold">Total income from all sources of family members contributing to the household.</h3>
                        <h4 class="mt-1 fw-bold">Note: <span class="text-danger">If not applicable, please enter NA for the name section and enter 0 for age and annual income field.</span></h4>
                        <div class="col-12">
                           <div class="table-responsive mt-4">
                              <table class="table table-bordered form-table">
                                 <thead>
                                    <tr>
                                       <th scope="col">Relationship</th>
                                       <th scope="col">Name</th>
                                       <th scope="col">Age</th>
                                       <th scope="col">Nature of Employment</th>
                                       <th scope="col">Annual Income (INR)</th>
                                       <th scope="col">ITR Status for last FY</th>
                                    </tr>
                                 </thead>
                                 <tbody id="familyTableBody">
                                    <tr class="family-row">
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-people"></i>
                                             </span>
                                             <input value="{{ $application->visaDetail->familyMembers->first()->relationship ?? '' }}" name="family[0][relationship]" type="text" class="form-control relationship"
                                                placeholder="Enter the Relationship">
                                          </div>
                                          @error('family.0.relationship')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-person"></i>
                                             </span>
                                             <input value="{{ $application->visaDetail->familyMembers->first()->name ?? '' }}" name="family[0][name]" type="text" class="form-control name"
                                                placeholder="Enter the Name">
                                          </div>
                                          @error('family.0.name')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-person-plus"></i>
                                             </span>
                                             <input value="{{ $application->visaDetail->familyMembers->first()->age ?? '' }}" name="family[0][age]" type="text" class="form-control age"
                                                placeholder="Enter the Age">
                                          </div>
                                          @error('family.0.age')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-wallet2"></i>
                                             </span>
                                             @php
                                             $employment = old(
                                             'family.0.employment',
                                             optional($application->visaDetail->familyMembers->first())->employment
                                             );
                                             @endphp
                                             <select name="family[0][employment]" class="form-select employment">
                                                <option value="">Select</option>
                                                <option value="Salaried" @selected($employment==='Salaried' )>Salaried</option>
                                                <option value="Retired" @selected($employment==='Retired' )>Retired</option>
                                                <option value="Self-Employed" @selected($employment==='Self-Employed' )>Self-Employed</option>
                                                <option value="Unemployed" @selected($employment==='Unemployed' )>Unemployed</option>
                                                <option value="Intern/Apprentice" @selected($employment==='Intern/Apprentice' )>Intern/Apprentice</option>
                                                <option value="Others" @selected($employment==='Others' )>Others</option>
                                             </select>
                                          </div>
                                          @error('family.0.employment')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-award"></i>
                                             </span>
                                             <input value="{{ $application->visaDetail->familyMembers->first()->income ?? '' }}" name="family[0][income]" type="text" class="form-control income"
                                                placeholder="Enter Income">
                                          </div>
                                          @error('family.0.income')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                       <td>
                                          <div class="input-group rounded-1">
                                             <span class="input-group-text bg-white text-blue">
                                                <i class="bi bi-wallet2"></i>
                                             </span>
                                             @php
                                             $itrStatus = optional($application->visaDetail->familyMembers->first())->itr_status;
                                             @endphp
                                             <select name="family[0][itrstatus]" class="form-select itrstatus">
                                                <option value="">Select</option>
                                                <option value="Filled" @selected($itrStatus==='Filled' )>Filled</option>
                                                <option value="Not Filled" @selected($itrStatus==='Not Filled' )>Not Filled</option>
                                             </select>
                                          </div>
                                          @error('family.0.itrstatus')
                                          <div class="text-danger mt-1">{{ $message }}</div>
                                          @enderror
                                       </td>
                                    </tr>
                                    <tr id="addMoreRow">
                                       <td colspan="6" class="text-end pt-2">
                                          <button type="button" class="next-btn fw-bold" id="addMoreBtn">
                                             + Add More
                                          </button>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="prev-btn fw-bold"><i class="bi bi-arrow-left" aria-hidden="true"></i> Previous</button>
                        <button type="submit" class="next-btn fw-bold">Save Next <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade {{ $activeTab == 'pills-documents-tab' ? 'show active' : '' }}" id="pills-documents" role="tabpanel" aria-labelledby="pills-documents-tab"
               tabindex="0">
               <form id="" action="" method="post" class="py-4 form-box">
                  <div class="form-step">
                     <p class="mt-1 mb-3 fw-bold">Note: <span class="text-danger"> Please upload all documents in
                           PDF
                           file of size Maximum 3 MB and upload photo and Signature in JPEG/JPG format of size
                           100
                           KB.</span>
                     </p>
                     <div class="row g-3">
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="aadhar" class="form-label fw-bold mb-2">Aadhar <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="aadhar" aria-label="Upload Aadhar"
                                 aria-describedby="aadharHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="casteCertificate" class="form-label fw-bold mb-2">Caste Certificate <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="casteCertificate"
                                 aria-label="Upload Caste Certificate"
                                 aria-describedby="casteCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="birthCertificate" class="form-label fw-bold mb-2">Birth Certificate <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="birthCertificate"
                                 aria-label="Upload Birth Certificate"
                                 aria-describedby="birthCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="addressProof" class="form-label fw-bold mb-2">Current Address Proof <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="addressProof"
                                 aria-label="Upload Address Proof" aria-describedby="addressProofHelp"
                                 required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="permanentAddress" class="form-label fw-bold mb-2">Permanent Address
                              Proof <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="permanentAddress"
                                 aria-label="Upload Permanent Address Proof"
                                 aria-describedby="permanentAddressHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="marksheet" class="form-label fw-bold mb-2">Highschool/10th/Secondary
                              Marksheet
                              <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="marksheet"
                                 aria-label="Upload Marksheet" aria-describedby="marksheetHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="qualifyingCertificate" class="form-label fw-bold mb-2">Qualifying
                              Degree/Provisional Certificate <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="qualifyingCertificate"
                                 aria-label="Upload Qualifying Certificate"
                                 aria-describedby="qualifyingCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="semesterMarksheets" class="form-label fw-bold mb-2">All Semester
                              Marksheets(Combined PDF) <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="semesterMarksheets"
                                 aria-label="Upload Semester Marksheets"
                                 aria-describedby="semesterMarksheetsHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="proofOfCGPA" class="form-label fw-bold mb-2">Proof of CGPA /SGPA
                              conversion
                              formula into percentage (in case percentage marks not given). <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="proofOfCGPA"
                                 aria-label="Upload Proof of CGPA /SGPA" aria-describedby="proofOfCGPAHelp"
                                 required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="offerLetter" class="form-label fw-bold mb-2">Unconditional offer letter
                              from
                              foreign University <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="offerLetter"
                                 aria-label="Upload Unconditional offer letter"
                                 aria-describedby="offerLetterHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="noc" class="form-label fw-bold mb-2">Employer's No Objection
                              Certificate(NOC)
                              Certificate(if employed) <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <p class="mt-1 mb-3 fw-bold">Note: <span class="text-danger">In case the candidate
                                 is
                                 unemployed, they may upload a note in place of the No Objection Certificate
                                 (NOC)</span>
                           </p>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="noc"
                                 aria-label="Upload No Objection Certificate" aria-describedby="nocHelp"
                                 required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="gapCertificate" class="form-label fw-bold mb-2">Gap Certificate <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <p class="mt-1 mb-3 fw-bold">Note: <span class="text-danger">If the gap period after
                                 completing the qualifying degree is less than six months, the applicant may
                                 upload a
                                 note stating this fact.</span>
                           </p>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="gapCertificate"
                                 aria-label="Upload Gap Certificate" aria-describedby="gapCertificateHelp"
                                 required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="familyIncomeCertificate" class="form-label fw-bold mb-2">Family Income
                              Certificate <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="familyIncomeCertificate"
                                 aria-label="Upload Family Income Certificate"
                                 aria-describedby="familyIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="familyIncomeCertificate" class="form-label fw-bold mb-2">Income Tax
                              Return (If
                              filling ITR,Combined PDF for all family members) <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <p class="mt-1 mb-3 fw-bold">Note: <span class="text-danger">If the applicant’s
                                 gross annual
                                 family income is less than ₹2.5 lakh under the Old Tax Regime or ₹3.0 lakh
                                 under the
                                 New Tax Regime for the relevant financial year (as per Clause 5 of the
                                 Scheme
                                 Guidelines for Selection Year 2025-26), the applicants may upload an income
                                 certificate in place of the Income Tax Return (ITR) acknowledgment
                                 document.</span>
                           </p>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="familyIncomeCertificate"
                                 aria-label="Upload Family Income Certificate"
                                 aria-describedby="familyIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="fatherIncomeCertificate" class="form-label fw-bold mb-2">Father Income
                              Certificate <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="fatherIncomeCertificate"
                                 aria-label="Upload Father Income Certificate"
                                 aria-describedby="fatherIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-12 mb-3">
                           <label for="motherIncomeCertificate" class="form-label fw-bold mb-2">Mother Income
                              Certificate <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="motherIncomeCertificate"
                                 aria-label="Upload Father Income Certificate"
                                 aria-describedby="motherIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="spouseIncomeCertificate" class="form-label fw-bold mb-2">Spouse Income
                              Certificate <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="spouseIncomeCertificate"
                                 aria-label="Upload Spouse Income Certificate"
                                 aria-describedby="spouseIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="selfIncomeCertificate" class="form-label fw-bold mb-2">Self Income
                              Certificate
                              <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="selfIncomeCertificate"
                                 aria-label="Upload Self Income Certificate"
                                 aria-describedby="selfIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="siblingOneIncomeCertificate" class="form-label fw-bold mb-2">Sibling or
                              Child
                              One Income Certificate <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="siblingOneIncomeCertificate"
                                 aria-label="Upload Sibling or Child One Income Certificate"
                                 aria-describedby="siblingOneIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="siblingTwoIncomeCertificate" class="form-label fw-bold mb-2">Sibling or
                              Child
                              Two Income Certificate <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="siblingTwoIncomeCertificate"
                                 aria-label="Upload Sibling or Child Two Income Certificate"
                                 aria-describedby="siblingTwoIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="siblingThreeIncomeCertificate" class="form-label fw-bold mb-2">Sibling
                              or Child
                              Three Income Certificate <sup class="text-danger"
                                 aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="siblingThreeIncomeCertificate"
                                 aria-label="Upload Sibling or Child Three Income Certificate"
                                 aria-describedby="siblingThreeIncomeCertificateHelp" required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="applicantPhoto" class="form-label fw-bold mb-2">Applicant Photo <sup
                                 class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="applicantPhoto"
                                 aria-label="Upload Applicant Photo" aria-describedby="applicantPhotoHelp"
                                 required>
                           </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-12 mb-3">
                           <label for="applicantSignature" class="form-label fw-bold mb-2">Applicant Signature
                              <sup class="text-danger" aria-hidden="true">*</sup></label>
                           <div class="input-group rounded-1 file-upload-group">
                              <span class="input-group-text bg-white text-blue"><i
                                    class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                              <input type="file" class="form-control" id="applicantSignature"
                                 aria-label="Upload Applicant Signature"
                                 aria-describedby="applicantSignatureHelp" required>
                           </div>
                        </div>
                     </div>
                     <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="prev-btn fw-bold"><i class="bi bi-arrow-left"
                              aria-hidden="true"></i> Previous</button>
                        <button type="button" class="next-btn fw-bold">Application Preview <i
                              class="bi bi-arrow-right" aria-hidden="true"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade {{ $activeTab == 'pills-preview-tab' ? 'show active' : '' }}" id="pills-preview" role="tabpanel" aria-labelledby="pills-preview-tab"
               tabindex="0">
               <form id="" action="" method="post" class="py-4 form-box">
                  <div class="form-step">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="table-responsive my-2">
                              <table class="table table-bordered preview-data">
                                 <thead>
                                    <tr>
                                       <th colspan="4" class="text-center text-blue fs-5">Application
                                          Preview
                                       </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <th>Applicant Name</th>
                                       <td>XYZ</td>
                                       <th>Father Name</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Gender</th>
                                       <td>Female</td>
                                       <th>D.O.B</th>
                                       <td>01 Jan 2000</td>
                                    </tr>
                                    <tr>
                                       <th>Mobile No.</th>
                                       <td>7569325484</td>
                                       <th>Email Id</th>
                                       <td>xyz@gmail.com</td>
                                    </tr>
                                    <tr>
                                       <th>Domicile State</th>
                                       <td>Delhi</td>
                                       <th>Domicile District</th>
                                       <td>South West</td>
                                    </tr>
                                    <tr>
                                       <th>Name of Board(10th/Highschool/Secondary)</th>
                                       <td>CBSE</td>
                                       <th>10th Board Certificate Number</th>
                                       <td>123</td>
                                    </tr>
                                    <tr>
                                       <th>Year of Passing</th>
                                       <td>2015</td>
                                       <th>Marital Status</th>
                                       <td>Unmarried</td>
                                    </tr>
                                    <tr>
                                       <th>Aadhar Number</th>
                                       <td>1236587</td>
                                       <th>Aadhar Enrollment ID(In case Aadhaar is not available and
                                          applied)
                                       </th>
                                       <td>1235786</td>
                                    </tr>
                                    <tr>
                                       <th>Current Address</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>State</th>
                                       <td>Delhi</td>
                                       <th>District</th>
                                       <td>South West</td>
                                    </tr>
                                    <tr>
                                       <th>Pin Code</th>
                                       <td colspan="3">110025</td>
                                    </tr>
                                    <tr>
                                       <th>Permanent Address</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>State</th>
                                       <td>Delhi</td>
                                       <th>District</th>
                                       <td>South West</td>
                                    </tr>
                                    <tr>
                                       <th>Pin Code</th>
                                       <td colspan="3">110025</td>
                                    </tr>
                                    <tr>
                                       <th>Emergency Contact Person Name</th>
                                       <td>XYZ</td>
                                       <th>Contact No.</th>
                                       <td>7566932148</td>
                                    </tr>
                                    <tr>
                                       <th>Email ID</th>
                                       <td>XYZ@gmail.com</td>
                                       <th>Relationship with Applicant</th>
                                       <td>Father</td>
                                    </tr>
                                    <tr>
                                       <th>Address</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th colspan="4" class="text-center bg-bluish text-blue">Foreign
                                          University/Institute Details
                                       </th>
                                    </tr>
                                    <tr>
                                       <th>Degree Course Applied for</th>
                                       <td>Master's Degree</td>
                                       <th>Field of Study</th>
                                       <td>Eccomerce</td>
                                    </tr>
                                    <tr>
                                       <th>Research Title</th>
                                       <td>XYZ</td>
                                       <th>Research Description</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Application/Registration/Admission date</th>
                                       <td>30 Jan 2020</td>
                                       <th>Anticipated Joining date</th>
                                       <td>30 Dec 2020</td>
                                    </tr>
                                    <tr>
                                       <th>Anticipiated Course End date</th>
                                       <td>30 Jan 2020</td>
                                       <th>Name of Institute/University</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Country</th>
                                       <td>India</td>
                                       <th>Course</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Name of College</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>College Address</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>State</th>
                                       <td>Delhi</td>
                                       <th>District</th>
                                       <td>New Delhi</td>
                                    </tr>
                                    <tr>
                                       <th>Subject/Course taken</th>
                                       <td>XYZ</td>
                                       <th>Year of Passing</th>
                                       <td>2015</td>
                                    </tr>
                                    <tr>
                                       <th>Scoring System </th>
                                       <td>100%</td>
                                       <th>Grading</th>
                                       <td>A+</td>
                                    </tr>
                                    <tr>
                                       <th>Details of Published research papers</th>
                                       <td colspan="3">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th colspan="4" class="text-center bg-bluish text-blue">
                                          Employment/Gap
                                          Details
                                       </th>
                                    </tr>
                                    <tr>
                                       <th>Currently Employed</th>
                                       <td>Yes</td>
                                       <th>Nature of Job</th>
                                       <td>It</td>
                                    </tr>
                                    <tr>
                                       <th>Office Name</th>
                                       <td>XYZ</td>
                                       <th>Office Address</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Office State</th>
                                       <td>Delhi</td>
                                       <th>Office District</th>
                                       <td>New Delhi</td>
                                    </tr>
                                    <tr>
                                       <th>Designation</th>
                                       <td>Software Developer</td>
                                       <th>Current Salary(Annual)</th>
                                       <td>9 LPA</td>
                                    </tr>
                                    <tr>
                                       <th>Have you ever been employed earlier?</th>
                                       <td>Yes</td>
                                       <th>Nature of Job</th>
                                       <td>It</td>
                                    </tr>
                                    <tr>
                                       <th>Office Name</th>
                                       <td>XYZ</td>
                                       <th>Office Address</th>
                                       <td>XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Office State</th>
                                       <td>Delhi</td>
                                       <th>Office District</th>
                                       <td>New Delhi</td>
                                    </tr>
                                    <tr>
                                       <th>Designation</th>
                                       <td>Software Developer</td>
                                       <th>Current Salary(Annual)</th>
                                       <td>9 LPA</td>
                                    </tr>
                                    <tr>
                                       <th>Joining Date</th>
                                       <td>30 Jan 2020</td>
                                       <th>Leaving Date</th>
                                       <td>30 Dec 2020</td>
                                    </tr>
                                    <tr>
                                       <th colspan="4" class="text-center bg-bluish text-blue">Visa
                                          Application/Income Details
                                       </th>
                                    </tr>
                                    <tr>
                                       <th>Have You/Any of your siblings was awarded this scholarship?</th>
                                       <td>Yes</td>
                                       <th>Number of Sibling's Awarded</th>
                                       <td>2</td>
                                    </tr>
                                    <tr>
                                       <th class="text-center bg-light">Name</th>
                                       <th class="text-center bg-light">Relationship with applicant</th>
                                       <th class="text-center bg-light">Year of Award</th>
                                       <th class="text-center bg-light">Course</th>
                                    </tr>
                                    <tr>
                                       <td class="text-center">XYZ</td>
                                       <td class="text-center">Brother</td>
                                       <td class="text-center">2012</td>
                                       <td class="text-center">XYZ</td>
                                    </tr>
                                    <tr>
                                       <th>Wheather applied for Visa?</th>
                                       <td colspan="3">Yes</td>
                                    </tr>
                                    <tr>
                                       <th>In case, Visa is applied for, whether it is obtained</th>
                                       <td>Yes</td>
                                       <th>Type of Visa</th>
                                       <td>2</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <div class="table-responsive my-2">
                              <table class="table table-bordered preview-data">
                                 <thead>
                                    <tr>
                                       <th class="text-center bg-light">Relationship</th>
                                       <th class="text-center bg-light">Name</th>
                                       <th class="text-center bg-light">Age</th>
                                       <th class="text-center bg-light">Nature of Employment</th>
                                       <th class="text-center bg-light">Annual Income (INR)</th>
                                       <th class="text-center bg-light">ITR Status for last FY</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td class="text-center">XYZ</td>
                                       <td class="text-center">Brother</td>
                                       <td class="text-center">2012</td>
                                       <td class="text-center">XYZ</td>
                                       <td class="text-center">XYZ</td>
                                       <td class="text-center">XYZ</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <div class="table-responsive my-2">
                              <table class="table table-bordered preview-data">
                                 <tbody>
                                    <tr>
                                       <th colspan="4" class="text-center bg-bluish text-blue">Upload
                                          Documents
                                       </th>
                                    </tr>
                                    <tr>
                                       <th>Aadhar</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Caste Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Birth Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Current Address Proof</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Permanent Address Proof</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Highschool/10th/Secondary marksheet</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Qualifying Degree/Provisional Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>All Semester Marksheets(Combined PDF)</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Proof of CGPA /SGPA conversion formula into percentage (in case
                                          percentage marks not given).
                                       </th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Unconditional offer letter from foreign University</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Employer's No Objection Certificate(NOC) Certificate(if
                                          employed)
                                       </th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Gap Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Family Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Income Tax Return (If filling ITR,Combined PDF for all family
                                          members)
                                       </th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Father Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Mother Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Spouse Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Self Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Sibling or Child One Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Sibling or Child Two Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th>Sibling or Child Three Income Certificate</th>
                                       <td><a href="#" target="_blank"
                                             class="theme-btn rounded-1 px-3 py-1">View
                                             PDF</a>
                                       </td>
                                    </tr>
                                    <tr>
                                       <th class="align-middle">Applicant Photo</th>
                                       <td class="align-middle"><img
                                             src="{{ asset('images/document.png') }}"
                                             class="applicant-photo" alt="Applicant Photo" /></td>
                                    </tr>
                                    <tr>
                                       <th class="align-middle">Applicant Signature</th>
                                       <td class="align-middle"><img
                                             src="{{ asset('images/signature.webp') }}" class="signature"
                                             alt="Signature Photo" /></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="prev-btn fw-bold"><i class="bi bi-arrow-left"
                              aria-hidden="true"></i> Previous</button>
                        <button type="button" class="next-btn fw-bold">Submit</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script>
   $(document).ready(function() {
      const $tabs = $(".stepper-form .nav-link");
      const $connectors = $(".stepper-form .connector");
      $tabs.on("shown.bs.tab", function() {
         const index = $tabs.index(this);
         $tabs.each(function(i) {
            const $tab = $(this);
            const $stepNum = $tab.find(".step-number");
            const $check = $tab.find(".check");
            // RESET ALL
            $tab.removeClass("completed");
            $stepNum.removeClass("d-none");
            $check.addClass("d-none");

            if ($connectors.eq(i).length) {
               $connectors.eq(i).removeClass("active");
            }

            // COMPLETED STEPS
            if (i < index) {
               $tab.addClass("completed");
               $stepNum.addClass("d-none");
               $check.removeClass("d-none");

               if ($connectors.eq(i).length) {
                  $connectors.eq(i).addClass("active");
               }
            }
         });
      });


      //NEXT BUTTON
      // $(document).on("click", ".next-btn", function(e) {
      //    e.preventDefault();
      //    const $activeTab = $(".stepper-form .nav-link.active");
      //    const $nextTab = $activeTab.closest("li").nextAll("li").find(".nav-link").first();
      //    if ($nextTab.length) {
      //       $nextTab.tab("show");
      //    }
      // });

      // PREVIOUS BUTTON
      $(document).on("click", ".prev-btn", function(e) {
         e.preventDefault();

         const $activeTab = $(".stepper-form .nav-link.active");
         const $prevTab = $activeTab.closest("li").prevAll("li").find(".nav-link").first();

         if ($prevTab.length) {
            $prevTab.tab("show");
         }
      });
   });
</script>
<script>
   (() => {
      // fetch district based on state
      $('#current_address_state').change(function() {
         var stateId = $(this).val();
         $('#current_address_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#current_address_district').html(options);
            });
         } else {
            $('#current_address_district').html('<option value="">Select District</option>');
         }
      });
      $('#permanent_address_state').change(function() {
         var stateId = $(this).val();
         $('#permanent_address_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#permanent_address_district').html(options);
            });
         } else {
            $('#permanent_address_district').html('<option value="">Select District</option>');
         }
      });
      $('#course_state').change(function() {
         var stateId = $(this).val();
         $('#course_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#course_district').html(options);
            });
         } else {
            $('#course_district').html('<option value="">Select District</option>');
         }
      });
      $('#permanent_address_state').change(function() {
         var stateId = $(this).val();
         $('#permanent_address_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#permanent_address_district').html(options);
            });
         } else {
            $('#permanent_address_district').html('<option value="">Select District</option>');
         }
      });
      $('#current_office_state').change(function() {
         var stateId = $(this).val();
         $('#current_office_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#current_office_district').html(options);
            });
         } else {
            $('#current_office_district').html('<option value="">Select District</option>');
         }
      });
      $('#employed_earlier_office_state').change(function() {
         var stateId = $(this).val();
         $('#employed_earlier_office_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#employed_earlier_office_district').html(options);
            });
         } else {
            $('#employed_earlier_office_district').html('<option value="">Select District</option>');
         }
      });
      $('#other_employment_office_state').change(function() {
         var stateId = $(this).val();
         $('#other_employment_office_district').html('<option>Loading...</option>');
         if (stateId) {
            $.get("{{ url('/districts') }}/" + stateId, function(data) {
               var options = '<option value="">Select District</option>';
               data = Array.isArray(data) ? data : Object.entries(data);
               data.forEach(function([code, name]) {
                  options += `<option value="${code}">${name}</option>`;
               });
               $('#other_employment_office_district').html(options);
            });
         } else {
            $('#other_employment_office_district').html('<option value="">Select District</option>');
         }
      });
      $("#personal-details").validate({
         ignore: [],
         errorClass: 'text-danger',
         errorElement: 'small',
         rules: {
            board: {
               required: true,
            },
            certificate_no: {
               required: true
            },
            year_of_passing: {
               required: true,
               digits: true,
               minlength: 4,
               maxlength: 4
            },
            marital_status: {
               required: true
            },
            aadhar: {
               required: function() {
                  return $("#aadhar_enrollment").val().trim() === "";
               },
               digits: true,
               minlength: 12,
               maxlength: 12
            },
            aadhar_enrollment: {
               required: function() {
                  return $("#aadhar").val().trim() === "";
               }
            },
            current_address_line1: {
               required: true
            },
            current_address_line2: {
               required: true
            },
            current_address_state: {
               required: true
            },
            current_address_district: {
               required: true
            },
            current_address_pincode: {
               required: true,
               digits: true,
               minlength: 6,
               maxlength: 6
            },
            permanent_address_line1: {
               required: true
            },
            permanent_address_line2: {
               required: true
            },
            permanent_address_state: {
               required: true
            },
            permanent_address_district: {
               required: true
            },
            permanent_address_pincode: {
               required: true,
               digits: true,
               minlength: 6,
               maxlength: 6
            },
            emergency_contact_person_name: {
               required: true
            },
            emergency_person_address: {
               required: true
            },
            emergency_person_contact_number: {
               required: true,
               digits: true,
               minlength: 10,
               maxlength: 10
            },
            emergency_person_contact_email: {
               required: true,
               email: true
            },
            relationship_applicant: {
               required: true
            }
         },
         messages: {
            board: {
               required: "Please enter board name",
            },
            certificate_no: {
               required: "Certificate number is required",
            },
            year_of_passing: {
               required: "Passing year is required",
               digits: "Passing year must contain digits only",
               minlength: "Passing year must be exactly 4 digits",
               maxlength: "Passing year must be exactly 4 digits"
            },
            marital_status: "Please select marital status",
            aadhar: {
               required: "Enter valid 12-digit Aadhaar number",
               digits: "Aadhaar number must contain digits only",
               minlength: "Aadhaar number must be exactly 12 digits",
               maxlength: "Aadhaar number must be exactly 12 digits"
            },
            aadhar_enrollment: "Aadhar Enrollment is required",
            current_address_line1: {
               required: "Please enter your current address (Line 1)"
            },
            current_address_line2: {
               required: "Please enter your current address (Line 2)"
            },
            current_address_state: {
               required: "Please select your current domicile state"
            },
            current_address_district: {
               required: "Please select your current domicile district"
            },
            current_address_pincode: {
               required: "Please enter current address PIN code",
               digits: "PIN code must contain digits only",
               minlength: "PIN code must be exactly 6 digits",
               maxlength: "PIN code must be exactly 6 digits"
            },
            permanenet_address_line1: {
               required: "Please enter your permanent address (Line 1)"
            },
            permanenet_address_line2: {
               required: "Please enter your permanent address (Line 2)"
            },
            permanent_address_state: {
               required: "Please select your permanent domicile state"
            },
            permanent_address_district: {
               required: "Please select your permanent domicile district"
            },
            permanent_address_pincode: {
               required: "Please enter permanent address PIN code",
               digits: "PIN code must contain digits only",
               minlength: "PIN code must be exactly 6 digits",
               maxlength: "PIN code must be exactly 6 digits"
            },
            emergency_contact_person_name: {
               required: "Please enter emergency contact person name"
            },
            emergency_person_address: {
               required: "Please enter emergency contact address"
            },
            emergency_person_contact_number: {
               required: "Please enter emergency contact number",
               digits: "Contact number must contain digits only",
               minlength: "Contact number must be exactly 10 digits",
               maxlength: "Contact number must be exactly 10 digits"
            },
            emergency_person_contact_email: {
               required: "Please enter emergency contact email address",
               email: "Please enter a valid email address"
            },
            relationship_applicant: {
               required: "Please specify relationship with applicant"
            }
         },
         highlight: function(element) {
            $(element).addClass('is-invalid');
         },
         unhighlight: function(element) {
            $(element).removeClass('is-invalid');
         },
         errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
               error.insertAfter(element.parent());
            } else {
               error.insertAfter(element);
            }
         }
      });

      $("#foreign-details").validate({
         ignore: [],
         errorClass: 'text-danger',
         errorElement: 'small',
         rules: {
            degree_course: {
               required: true
            },
            study_field: {
               required: true,
               minlength: 4,
               maxlength: 50
            },
            research_title: {
               required: true,
               minlength: 4,
               maxlength: 50
            },
            description: {
               required: true,
               minlength: 4,
               maxlength: 255
            },
            application_date: {
               required: true,
               date: true
            },
            anticipated_joining_date: {
               required: true,
               date: true
            },
            anticipated_course_end_date: {
               required: true,
               date: true
            },
            university: {
               required: true
            },
            country: {
               required: true
            },
            course: {
               required: true
            },
            college_name: {
               required: true,
               maxlength: 255
            },
            course_state: {
               required: true
            },
            course_district: {
               required: true
            },
            college_address: {
               required: true
            },
            course_taken: {
               required: true,
               maxlength: 255
            },
            passing_year: {
               required: true
            },
            scoring_system: {
               required: true
            },
            marks: {
               required: true,
               number: true,
               min: 0,
               max: 100
            },
            research_detail_paper: {
               required: true,
               minlength: 4,
               maxlength: 255
            }
         },
         messages: {
            degree_course: "Degree Course Applied field is required",
            study_field: {
               required: "Field of Study is required",
               minlength: "Field of Study min be 4 character",
               maxlength: "Field of Study max be 50 character"
            },
            research_title: {
               required: "Research title is required",
               minlength: "Research title min be 4 character",
               maxlength: "Research title max be 50 character"
            },
            description: {
               required: "Description is required",
               minlength: "Description min be 4 character",
               maxlength: "Description max be 50 character"
            },
            application_date: "Please select application date",
            anticipated_joining_date: "Please select joining date",
            anticipated_course_end_date: "Please select course end date",
            university: "Please select a university",
            country: "Please select a country",
            course: "Please select a course",
            college_name: "College name is required",
            course_state: "Please select course state",
            course_district: "Please select course district",
            college_address: "College address is required",
            course_taken: "Course taken is required",
            passing_year: {
               required: "Passing year is required"
            },
            scoring_system: "Please select scoring system",
            marks: {
               required: "Marks are required",
               number: "Marks must be a number",
               min: "Marks cannot be less than 0",
               max: "Marks cannot exceed 100"
            },
            research_detail_paper: {
               required: "Research detail is required",
               minlength: "Research detail min be 4 character",
               maxlength: "Research detail max be 255 character"
            },
         },

         highlight: function(element) {
            $(element).addClass('is-invalid');
         },
         unhighlight: function(element) {
            $(element).removeClass('is-invalid');
         },
         errorPlacement: function(error, element) {
            if (element.attr("type") === "radio") {
               error.insertAfter(element.closest('.radio-field'));
            } else if (element.parent('.input-group').length) {
               error.insertAfter(element.parent());
            } else {
               error.insertAfter(element);
            }
         }
      });
      // employment form validation
      $("#employment-form").validate({
         ignore: [],
         errorClass: 'text-danger',
         errorElement: 'small',
         rules: {
            currentlyEmployed: {
               required: true
            },
            current_job_nature: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            current_office_name: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            current_office_address: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               },
               maxlength: 500
            },
            current_office_state: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               }
            },
            current_office_district: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               }
            },
            current_office_designation: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            current_annual_salary: {
               required: {
                  depends: function() {
                     return $("input[name='currentlyEmployed']:checked").val() === "yes";
                  }
               },
               number: true,
               min: 0
            },
            employed_earlier: {
               required: true
            },
            employed_earlier_job_nature: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            employed_earlier_office: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            employed_earlier_office_address: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               },
               maxlength: 500
            },
            employed_earlier_office_state: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               }
            },
            employed_earlier_office_district: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               }
            },
            employed_earlier_office_designation: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            employed_earlier_salary: {
               required: {
                  depends: function() {
                     return $("input[name='employed_earlier']:checked").val() === "yes";
                  }
               },
               number: true,
               min: 0
            },
            other_employment: {
               required: true
            },
            other_employment_job_nature: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            other_employment_job_office: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            other_employment_office_address: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               maxlength: 500
            },
            other_employment_office_state: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               }
            },
            other_employment_office_district: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               }
            },
            other_employment_office_designation: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               maxlength: 255
            },
            other_employment_salary: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               number: true,
               min: 0
            },
            other_employment_joining_date: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               date: true
            },
            other_employment_leaving_date: {
               required: {
                  depends: function() {
                     return $("input[name='other_employment']:checked").val() === "yes";
                  }
               },
               date: true
            }
         },
         messages: {
            currentlyEmployed: "Please specify if you are currently employed.",
            current_job_nature: {
               required: "Please describe the nature of your current job.",
               maxlength: "Job nature cannot exceed 255 characters."
            },
            current_office_name: "Please enter your current office/organization name.",
            current_office_address: "Please provide the full office address.",
            current_office_state: "Please select the state of your current office.",
            current_office_district: "Please select the district of your current office.",
            current_office_designation: "Please enter your current job designation.",
            current_annual_salary: {
               required: "Please enter your annual salary.",
               number: "Please enter a valid numeric amount.",
               min: "Salary cannot be less than 0."
            },
            employed_earlier: "Please specify if you were employed previously.",
            employed_earlier_job_nature: "Please describe the nature of your previous job.",
            employed_earlier_office: "Please enter the previous office/employer name.",
            employed_earlier_office_address: "Please provide the previous office address.",
            employed_earlier_office_state: "Please select the state of your previous office.",
            employed_earlier_office_district: "Please select the district of your previous office.",
            employed_earlier_office_designation: "Please enter your previous designation.",
            employed_earlier_salary: {
               required: "Please enter your previous salary.",
               number: "Please enter a valid numeric amount.",
               min: "Salary cannot be less than 0."
            },
            other_employment: "Please specify if you have any other employment details.",
            other_employment_job_nature: "Please enter the job nature for this role.",
            other_employment_job_office: "Please enter the office name.",
            other_employment_office_address: "Please enter the office address.",
            other_employment_office_state: "Please select the office state.",
            other_employment_office_district: "Please select the office district.",
            other_employment_office_designation: "Please enter your designation.",
            other_employment_salary: {
               required: "Please enter the salary for this role.",
               number: "Please enter a valid numeric amount.",
               min: "Salary cannot be less than 0."
            },
            other_employment_joining_date: {
               required: "Please select the joining date.",
               date: "Please enter a valid date."
            },
            other_employment_leaving_date: {
               required: "Please select the leaving date.",
               date: "Please enter a valid date."
            }
         },
         highlight: function(element) {
            $(element).addClass('is-invalid');
         },
         unhighlight: function(element) {
            $(element).removeClass('is-invalid');
         },
         errorPlacement: function(error, element) {
            if (element.attr("type") === "radio") {
               error.insertAfter(element.closest('.radio-field'));
            } else if (element.parent('.input-group').length) {
               error.insertAfter(element.parent());
            } else {
               error.insertAfter(element);
            }
         }
      });

      function toggleEmploymentFields() {
         const employed = $('input[name="currentlyEmployed"]:checked').val();

         if (employed === 'yes') {
            $('#employment-fields').slideDown();
         } else {
            $('#employment-fields').slideUp();
         }
      }
      $('input[name="currentlyEmployed"]').on('change', toggleEmploymentFields);
      toggleEmploymentFields();


      function toggleEarlierEmploymentFields() {
         const employedEarlier = $('input[name="employed_earlier"]:checked').val();

         if (employedEarlier === 'yes') {
            $('#earlier-employment-fields').slideDown();
         } else {
            $('#earlier-employment-fields').slideUp();
         }
      }

      // On radio change
      $('input[name="employed_earlier"]').on('change', toggleEarlierEmploymentFields);

      // On page load (edit / old data)
      toggleEarlierEmploymentFields();

      function toggleOtherEmploymentFields() {
         const otherEmployment = $('input[name="other_employment"]:checked').val();

         if (otherEmployment === 'yes') {
            $('#other-employment-fields').slideDown();
         } else {
            $('#other-employment-fields').slideUp();
         }
      }
      $('input[name="other_employment"]').on('change', toggleOtherEmploymentFields);
      toggleOtherEmploymentFields();

      function toggleScholarshipSection() {
         const value = $('#scholarshipSelect').val();
         if (value === 'yes') {
            $('#scholarship-details-column, #scholarship-details-table')
               .slideDown()
               .attr('aria-hidden', 'false');
         } else {
            $('#scholarship-details-column, #scholarship-details-table').slideUp().attr('aria-hidden', 'true');
         }
      }
      $('#scholarshipSelect').on('change', toggleScholarshipSection);
      toggleScholarshipSection();

      function toggleVisaSection() {
         const visaApplied = $('#visaAppliedSelect').val();
         if (visaApplied === 'yes') {
            $('#visa-details-section').slideDown();
         } else {
            $('#visa-details-section').slideUp();
         }
      }
      $('#visaAppliedSelect').on('change', toggleVisaSection);
      toggleVisaSection();

      function toggleVisaTypeSection() {
         const value = $('#visaObtainedSelect').val();
         if (value === 'yes') {
            $('#visa-type-section').slideDown();
         } else {
            $('#visa-type-section').slideUp();
         }
      }
      $('#visaObtainedSelect').on('change', toggleVisaTypeSection);
      toggleVisaTypeSection();
      // Get siblings array from PHP
      const siblings = @json($application->visaDetail->siblings ?? []);
      console.warn("Siblings from DB:", siblings);

      function generateSiblingRows(count) {
         const $tbody = $('#scholarshipTableBody');
         const currentRows = $tbody.find('tr').length;
         if (count > 5) {
            Swal.fire(
               'Error!',
               'Maximum 5 siblings allowed',
               'error'
            );
            return;
         }
         if (currentRows > count) {
            $tbody.find('tr:gt(' + (count - 1) + ')').remove();
         }
         for (let i = currentRows; i < count; i++) {
            let siblingData = {};
            if (i < siblings.length) {
               siblingData = siblings[i];
            }
            $tbody.append(`
            <tr>
                <td>
                    <div class="input-group rounded-1">
                        <span class="input-group-text bg-white text-blue">
                            <i class="bi bi-person" aria-hidden="true"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="siblings[${i}][name]"
                               placeholder="Enter the Name"
                               value="${i < siblings.length ? siblingData.name || '' : ''}"
                               required>
                    </div>
                </td>
                <td>
                    <div class="input-group rounded-1">
                        <span class="input-group-text bg-white text-blue">
                            <i class="bi bi-people" aria-hidden="true"></i>
                        </span>
                        <select class="form-select"
                                name="siblings[${i}][relationship]"
                                required>
                            <option value="">Select</option>
                            <option value="Brother" ${i < siblings.length && siblingData.relationship === 'Brother' ? 'selected' : ''}>Brother</option>
                            <option value="Sister" ${i < siblings.length && siblingData.relationship === 'Sister' ? 'selected' : ''}>Sister</option>
                            <option value="Self" ${i < siblings.length && siblingData.relationship === 'Self' ? 'selected' : ''}>Self</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group rounded-1">
                        <span class="input-group-text bg-white text-blue">
                            <i class="bi bi-calendar2-week" aria-hidden="true"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="siblings[${i}][year_of_award]"
                               placeholder="Enter the Year of Award"
                               value="${i < siblings.length ? siblingData.year_of_award || '' : ''}" 
                               required>
                    </div>
                </td>
                <td>
                    <div class="input-group rounded-1">
                        <span class="input-group-text bg-white text-blue">
                            <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="siblings[${i}][course]"
                               placeholder="Enter the Course Name"
                               value="${i < siblings.length ? siblingData.course || '' : ''}"
                               required>
                    </div>
                </td>
            </tr>
        `);
         }
      }

      // Event listener for input changes
      $('#no_of_sibling_awarded').on('input', function() {
         const count = parseInt($(this).val()) || 0;
         generateSiblingRows(count);
      });

      // On page load, generate rows if input has value
      $(document).ready(function() {
         const initialCount = parseInt($('#no_of_sibling_awarded').val()) || 0;
         if (initialCount > 0) {
            generateSiblingRows(initialCount);
         }
      });
      const familyMembers = @json($application->visaDetail->familyMembers ?? []);
      console.warn("family members", familyMembers);
      let rowIndex = $(".family-row").length;
      $("#addMoreBtn").on("click", function() {
         let $clone = $(".family-row:first").clone();
         $clone.attr("id", "family-row-" + rowIndex);
         $clone.find("input, select").each(function() {
            let name = $(this).attr("name");
            if (name) {
               let newName = name.replace(/\[\d+\]/, "[" + rowIndex + "]");
               $(this).attr("name", newName);
            }
            if (this.tagName === "INPUT") {
               $(this).val("");
            } else {
               this.selectedIndex = 0;
            }
         });
         $("#addMoreRow").before($clone);
         rowIndex++;
      });
      if (familyMembers.length > 1) {
         for (let i = 1; i < familyMembers.length; i++) {
            $("#addMoreBtn").trigger("click");
            const member = familyMembers[i];
            const row = $(".family-row").eq(i);
            row.find('[name$="[relationship]"]').val(member.relationship);
            row.find('[name$="[name]"]').val(member.name);
            row.find('[name$="[age]"]').val(member.age);
            row.find('[name$="[employment]"]').val(member.employment);
            row.find('[name$="[income]"]').val(member.income);
            row.find('[name$="[itrstatus]"]').val(member.itr_status);
         }
      }
   })();
</script>
@endpush