@extends('layouts.app')
@section('title', 'Login')
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
        <div class="row justify-content-center mt-4">
            <div class="col-xl-7 col-lg-7 col-md-6 p-0">
                <div class="form-box bg-white p-4 p-md-5 h-100 rounded-start">
                    <h2 class="text-blue fw-bold mb-4 text-center text-uppercase">Login</h2>
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <form id="login-form" action="{{route('login')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <x-text-input
                                    name="email"
                                    type="email"
                                    placeholder="Enter your email"
                                    label="Login Id"
                                    icon="bi-envelope"
                                    autocomplete="new-email" />
                            </div>
                            <div class="col-lg-12 mb-3">
                                <x-text-input
                                    name="password"
                                    type="password"
                                    placeholder="Enter your password"
                                    label="Password"
                                    icon="bi-lock"
                                    autocomplete="new-password" />
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12 col-12 mb-lg-0 mb-3">
                                <div class="captcha captcha-section p-0 mt-2">
                                    {!! NoCaptcha::display() !!}
                                </div>
                            </div>
                            <!-- <div class="col-lg-6 col-md-12 col-12">
                                <x-text-input
                                    name="captcha"
                                    type="text"
                                    placeholder="Enter Captcha Code"
                                    label="Captcha"
                                    icon="bi-card-text" />
                            </div> -->
                            <div class="col-lg-12 mt-4 text-center">
                                <button type="submit" class="loginbtn px-4 py-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 p-0">
                <div class="img-box bg-blue p-4 p-md-5 h-100 rounded-end d-flex align-items-center">
                    <img src="{{asset('images/login.png')}}" alt="Login" class="img-fluid" />
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
        $("#login-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                },
                'g-recaptcha-response': {
                    captchaRequired: true
                }
            },
            messages: {
                email: {
                    required: "Please enter your email.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please enter your password.",
                    minlength: "Password must be at least 6 characters."
                },
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
        jQuery.validator.addMethod("captchaRequired", function(value, element) {
            return grecaptcha.getResponse().length > 0;
        }, "Please complete the captcha.");
    });
</script>
@endpush