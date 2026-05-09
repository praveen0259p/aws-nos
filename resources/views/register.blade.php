@extends('layouts.app')
@section('title', 'Register')
@section('content')
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
<section class="py-5 bg-grey-light">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9 mt-3">
                <div class="form-box bg-white p-4 p-md-5 h-100 rounded">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <h2 class="text-blue fw-bold mb-4 text-center text-uppercase">Form</h2>
                    <form id="register-form" action="{{route('student.register')}}" method="post">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="round" value="{{ $round }}">
                            <div class="col-lg-6 mb-3">
                                <x-text-input 
                                    name="firstname" 
                                    type="text" 
                                    placeholder="Enter your first name" 
                                    label="First Name" 
                                    icon="bi-person" 
                                    aria-autocomplete="off"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-text-input 
                                    name="middlename" 
                                    type="text" 
                                    placeholder="Enter your middle name" 
                                    label="Middle Name" 
                                    icon="bi-person" 
                                    aria-autocomplete="off"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-text-input 
                                    name="lastname" 
                                    type="text" 
                                    placeholder="Enter your last name" 
                                    label="Last Name" 
                                    icon="bi-person" 
                                    aria-autocomplete="off"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-text-input 
                                    name="father_name" 
                                    type="text" 
                                    placeholder="Enter your father name" 
                                    label="Father Name" 
                                    icon="bi-person" 
                                    aria-autocomplete="off"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-select-input 
                                    name="gender" 
                                    icon="bi-grid-fill" 
                                    label="Select Gender" 
                                    :options="genderOptions()" 
                                    placeholder="Choose Gender"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-text-input 
                                    name="dob" 
                                    type="date" 
                                    placeholder="Date of Birth" 
                                    label="Date of Birth" 
                                    icon="bi-calendar-event" 
                                    aria-autocomplete="off"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                 <x-text-input 
                                    name="mobile" 
                                    type="text" 
                                    placeholder="Enter your Mobile No" 
                                    label="Mobile No" 
                                    icon="bi-telephone" 
                                    aria-autocomplete="off"
                                    minlength="10"
                                    maxlength="10"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                 <x-text-input 
                                    name="email" 
                                    type="email" 
                                    placeholder="Enter your Email" 
                                    label="Email" 
                                    icon="bi-envelope" 
                                    autocomplete="new-email"
                                /> 
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-select-input 
                                    name="category" 
                                    icon="bi-grid-fill" 
                                    label="Select Category" 
                                    :options="categoryOptions()" 
                                    placeholder="Choose category"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-select-input 
                                    name="state"
                                    icon="bi-grid-fill"  
                                    label="Select State" 
                                    :options="getAllState()" 
                                    placeholder="Choose State"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <x-select-input 
                                    name="district"
                                    icon="bi-grid-fill"  
                                    label="Select District" 
                                    placeholder="Select District"
                                />
                            </div>
                            <div class="col-lg-6 mb-3">
                                 <x-text-input 
                                    name="password" 
                                    type="password" 
                                    placeholder="Enter password" 
                                    label="Password" 
                                    icon="bi-eye-fill" 
                                    autocomplete="new-password"
                                /> 
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-12 mb-md-0 mb-3">
                                <div class="captcha captcha-section p-0 mt-2">
                                    {!! NoCaptcha::display() !!}
                                </div>
                            </div>
                            <!-- <div class="col-lg-6 col-md-6 col-12">
                                <x-text-input 
                                    name="captcha" 
                                    type="text" 
                                    placeholder="Enter Captcha Code" 
                                    label="Captcha" 
                                    icon="bi-card-text" 
                                />
                            </div> -->
                            <div class="col-lg-12 mt-4 text-center">
                                <button type="submit" class="loginbtn px-4 py-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/core.js') }}"></script>
<script src="{{ asset('js/sha256.js') }}"></script>
{!! NoCaptcha::renderJs() !!}
<script>
$(document).ready(function() {
    $('#state').change(function() {
    var stateId = $(this).val();
    $('#district').html('<option>Loading...</option>');
    if(stateId) {
        $.get("{{ url('/districts') }}/"+stateId, function(data){
            var options = '<option value="">Select District</option>';
            data = Array.isArray(data) ? data : Object.entries(data);
            console.warn(data);
            data.forEach(function([code, name]){
                options += `<option value="${code}">${name}</option>`;
            });
            $('#district').html(options);
        });
    } else {
        $('#district').html('<option value="">Select District</option>');
    }
});
    $("#register-form").validate({
        ignore: [],
        errorClass: 'text-danger',
        errorElement: 'small',
        rules: {
            firstname: {
                required: true,
                lettersonly: true,
                maxlength: 50
            },
            middlename: {
                lettersonly: true,
                maxlength: 50
            },
            lastname: {
                required: true,
                lettersonly: true,
                maxlength: 50
            },
            father_name: {
                required: true,
                lettersonly: true,
                maxlength: 50
            },
            gender: {
                required: true
            },
            dob: {
                required: true,
                date: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            email: {
                required: true,
                email: true
            },
            category: {
                required: true
            },
            state: {
                required: true
            },
            district: {
                required: true
            },
            password: {
                required: true,
                minlength: 6
            },
            'g-recaptcha-response': {
                captchaRequired: true
            }
        },
        messages: {
            firstname: {
                required: "Please enter your first name.",
                lettersonly: "First name can only contain letters.",
                maxlength: "Maximum 50 characters allowed."
            },
            middlename: {
                lettersonly: "Middle name can only contain letters.",
                maxlength: "Maximum 50 characters allowed."
            },
            lastname: {
                required: "Please enter your last name.",
                lettersonly: "Last name can only contain letters.",
                maxlength: "Maximum 50 characters allowed."
            },
            father_name: {
                required: "Please enter your father name.",
                lettersonly: "Father name can only contain letters.",
                maxlength: "Maximum 50 characters allowed."
            },
            gender: {
                required: "Please select your gender."
            },
            dob: {
                required: "Please enter your date of birth.",
                date: "Please enter a valid date."
            },
            mobile: {
                required: "Please enter your mobile number.",
                digits: "Mobile must contain only digits.",
                minlength: "Mobile must be 10 digits.",
                maxlength: "Mobile must be 10 digits."
            },
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email address."
            },
            category: {
                required: "Please select a category."
            },
            state: {
                required: "Please select a state."
            },
            district: {
                required: "Please select a district."
            },
            password: {
                required: "Please enter a password.",
                minlength: "Password must be at least 6 characters."
            }
            // captcha: {
            //     required: "Please enter the captcha."
            // }
        },
        submitHandler: function(form) {
            var pass = $(form).find('input[name="password"]').val();
            var hashText = CryptoJS.SHA256(pass);
            $(form).find('input[name="password"]').val(hashText);
            form.submit();
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") === "g-recaptcha-response") {
                error.insertAfter($('.captcha-section'));
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z]+$/.test(value);
    }, "Letters only please"); 
    jQuery.validator.addMethod("captchaRequired", function(value, element) {
        return grecaptcha.getResponse().length > 0;
    }, "Please complete the captcha.");
});
</script>
@endpush