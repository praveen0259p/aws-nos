@extends('layouts.app')
@section('title', 'Register')

@push('styles')
<style>
    .stepper {
        position: relative;
    }

    .stepper::before {
        content: '';
        position: absolute;
        top: 40px;
        bottom: 40px;
        left: 20px;
        width: 4px;
        background: #e9ecef;
    }

    .step {
        margin-bottom: 2rem;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        color: #6c757d;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .step.active .step-number {
        background: #0d6efd;
        color: #fff;
    }

    .step.completed .step-number {
        background: #198754;
        color: #fff;
    }

    .form-box {
        display: none;
    }

    .form-box.active {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">Multi Step Form</h2>

    <div class="row">
        <!-- Stepper -->
        <div class="col-md-3">
            <div class="stepper">
                <div class="step {{ $activeStep >= 1 ? 'active completed' : '' }}">
                    <div class="d-flex">
                        <div class="step-number">1</div>
                        <div class="ms-2">Personal</div>
                    </div>
                </div>
                <div class="step {{ $activeStep >= 2 ? 'active' : '' }}">
                    <div class="d-flex">
                        <div class="step-number">2</div>
                        <div class="ms-2">Address</div>
                    </div>
                </div>
                <div class="step {{ $activeStep >= 3 ? 'active' : '' }}">
                    <div class="d-flex">
                        <div class="step-number">3</div>
                        <div class="ms-2">Payment</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="col-md-9">

            <!-- STEP 1 -->
            <form id="step1Form" class="form-box {{ $activeStep == 1 ? 'active' : '' }}">
                @csrf
                <h4>Step 1: Personal Info</h4>
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $application->name ?? '' }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $application->email ?? '' }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Draft & Next</button>
            </form>

            <!-- STEP 2 -->
            <form id="step2Form" class="form-box {{ $activeStep == 2 ? 'active' : '' }}">
                @csrf
                <h4>Step 2: Address</h4>

                <div class="mb-3"> 
                    <label>Street</label>
                    <input type="text" name="street" class="form-control">
                </div>

                <div class="mb-3">
                    <label>City</label>
                    <input type="text" name="city" class="form-control">
                </div>

                <button type="button" class="btn btn-secondary prev">Previous</button>
                <button class="btn btn-primary">Draft & Next</button>
            </form>

            <!-- STEP 3 -->
            <form id="step3Form" class="form-box {{ $activeStep == 3 ? 'active' : '' }}">
                @csrf
                <h4>Step 3: Payment</h4>

                <div class="mb-3">
                    <label>Card Number</label>
                    <input type="text" name="card_number" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Expiry</label>
                    <input type="text" name="expiry" class="form-control">
                </div>

                <button type="button" class="btn btn-secondary prev">Previous</button>
                <button class="btn btn-success">Finish</button>
            </form>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        const steps = [{
                form: '#step1Form',
                next: '#step2Form',
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    name: "Please enter your Name.",
                    email: "Please enter a valid email address."
                }
            },
            {
                form: '#step2Form',
                next: '#step3Form',
                rules: {
                    street: {
                        required: true
                    },
                    city: {
                        required: true
                    }
                },
                messages: {
                    street: "Street is required",
                    city: "City is required"
                }
            },
            {
                form: '#step3Form',
                next: null,
                rules: {
                    card_number: {
                        required: true,
                        creditcard: true
                    },
                    expiry: {
                        required: true
                    }
                },
                messages: {
                    card_number: "Invalid card number",
                    expiry: "Expiry required"
                }
            }
        ];

        steps.forEach((step, index) => {
            $(step.form).validate({
                //rules: step.rules,
                //messages: step.messages,
                errorClass: 'text-danger',
                submitHandler: function(form) {
                    const stepIndex = index + 1;
                    let formData = $(form).serializeArray();
                    // formData.push({ name: 'year', value: activePortal.year });
                    // formData.push({ name: 'round', value: activePortal.round });
                    $(form).find('.text-danger.backend-error').remove();
                    $.ajax({
                        url: "{{ url('/register/step') }}/" + stepIndex,
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            $(form).removeClass('active');
                            if (step.next) {
                                $(step.next).addClass('active');
                                $('.step').eq(index).addClass('completed');
                                $('.step').eq(index + 1).addClass('active');
                            } else {
                                alert('All steps completed successfully!');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (let field in errors) {
                                    let $input = $(form).find('[name="' + field + '"]');
                                    $input.after('<div class="text-danger backend-error">' + errors[field][0] + '</div>');
                                }
                            }
                        }
                    });
                }
            });
        });

        // PREVIOUS BUTTON
        $('.prev').on('click', function() {
            const currentForm = $(this).closest('form');
            const currentIndex = steps.findIndex(s => s.form === '#' + currentForm.attr('id'));
            if (currentIndex > 0) {
                currentForm.removeClass('active');
                $(steps[currentIndex - 1].form).addClass('active');

                // Update stepper
                $('.step').removeClass('active');
                $('.step').eq(currentIndex - 1).addClass('active');
            }
        });


    });
</script>

@endpush