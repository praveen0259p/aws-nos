@extends('backend.layouts.app')
@section('title', 'Create User')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Create User</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('users.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="user-form" action="{{route('users.save')}}" method="post" class="form-box" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <x-text-input
                        name="password"
                        type="hidden"
                        :required="false" />
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="firstname"
                            type="text"
                            placeholder="First Name"
                            label="First Name"
                            icon="bi-card-checklist"
                            autocomplete="First Name"
                            :required="true" />
                    </div>

                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="middlename"
                            type="text"
                            placeholder="Middle Name"
                            label="Middle Name"
                            icon="bi-list-ol"
                            autocomplete="Middle Name"
                            :required="false" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="lastname"
                            type="text"
                            placeholder="Last Name"
                            label="Last Name"
                            icon="bi-list-ol"
                            autocomplete="Last Name"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="email"
                            type="email"
                            placeholder="Email"
                            label="Email"
                            icon="bi-list-ol"
                            autocomplete="Email"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="mobile"
                            type="number"
                            placeholder="Mobile No"
                            label="Mobile No"
                            icon="bi-list-ol"
                            autocomplete="Mobile No"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="gender"
                            icon="bi-check2-circle"
                            label="Select Gender"
                            :options="genderOptions()"
                            placeholder="Select Gender" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="role"
                            icon="bi-check2-circle"
                            label="Select Role"
                            :options="$roles"
                            placeholder="Select Role" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="status"
                            icon="bi-check2-circle"
                            label="Select Status"
                            :options="statusoptions()"
                            placeholder="Select Status" />
                    </div>
                    <div class="col-lg-12 my-4">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <button type="submit" class="cms-btn rounded-2">Submit</button>
                            <button type="reset" class="border-btn rounded-2">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/core.js') }}"></script>
<script src="{{ asset('js/sha256.js') }}"></script>
<script>
    (() => {
        $("#user-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                firstname: {
                    required: true,
                    lettersonly: true
                },
                middlename: {
                    lettersonly: true
                },
                lastname: {
                    required: true,
                    lettersonly: true
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    digits: true
                },
                gender: {
                    required: true
                },
                role: {
                    required: true
                },
                status: {
                    required: true,
                    digits: true,
                    range: [0, 1]
                }
            },
            messages: {
                firstname: {
                    required: "Enter First Name"
                },
                lastname: {
                    required: "Enter Last Name"
                },
                email: {
                    required: "Enter Email",
                    email: "Enter a valid Email"
                },
                mobile: {
                    required: "Enter Mobile Number",
                    digits: "Mobile must be digits only"
                },
                gender: {
                    required: "Please select Gender"
                },
                role: {
                    required: "Please select role"
                },
                status: {
                    required: "Please Choose Status",
                    digits: "Status must be an integer",
                    range: "Invalid status selected"
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
    })();
</script>
@endpush