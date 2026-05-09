@extends('layouts.app')
@section('title', 'Register')
@section('content')
@push('styles')
<style>
        /* Vertical Timeline Stepper */
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
            z-index: 0;
        }
        .step {
            position: relative;
            z-index: 1;
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
            margin-right: 1rem;
        }
        .step.active .step-number {
            background: #0d6efd;
            color: white;
        }
        .step.completed .step-number {
            background: #198754;
            color: white;
        }
        .step.completed .step-number::after {
            content: '✓';
        }

        /* Form Steps */
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
    </style>
    @endpush
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
    <div class="container py-5">
    <h2 class="text-center mb-5">Project Timeline</h2>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="row">
                        <!-- Vertical Stepper -->
                        <div class="col-md-3">
                            <div class="stepper">
                                <div class="step active completed">
                                    <div class="d-flex align-items-center">
                                        <div class="step-number">1</div>
                                        <div>Personal Info</div>
                                    </div>
                                </div>
                                <div class="step">
                                    <div class="d-flex align-items-center">
                                        <div class="step-number">2</div>
                                        <div>Address</div>
                                    </div>
                                </div>
                                <div class="step">
                                    <div class="d-flex align-items-center">
                                        <div class="step-number">3</div>
                                        <div>Payment</div>
                                    </div>
                                </div>
                                <div class="step">
                                    <div class="d-flex align-items-center">
                                        <div class="step-number">4</div>
                                        <div>Confirmation</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="col-md-9">
                            <form id="multiStepForm">
                                <!-- Step 1 -->
                                <div class="form-step active">
                                    <h4>Step 1: Personal Information</h4>
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" required>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary next-btn">Next</button>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="form-step">
                                    <h4>Step 2: Address</h4>
                                    <div class="mb-3">
                                        <label class="form-label">Street</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                        <button type="button" class="btn btn-primary next-btn">Next</button>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="form-step">
                                    <h4>Step 3: Payment Details</h4>
                                    <div class="mb-3">
                                        <label class="form-label">Card Number</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Expiry</label>
                                        <input type="text" class="form-control" placeholder="MM/YY" required>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                        <button type="button" class="btn btn-primary next-btn">Next</button>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="form-step">
                                    <h4>Step 4: Confirmation</h4>
                                    <p>Review your information and submit.</p>
                                    <div class="alert alert-info">
                                        All previous steps will be summarized here (you can populate dynamically with JS).
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                        <button type="submit" class="btn btn-success">Submit Form</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    const $form = $('#multiStepForm');
    const $steps = $('.form-step');
    const $stepItems = $('.step');
    const $summary = $('#summary');
    let currentStep = 0;

    // Initialize jQuery Validation
    $form.validate({
        errorElement: 'small',
        errorClass: 'error',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        rules: {
            full_name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            phone: { phoneUS: true },
            street: "required",
            city: "required",
            zip: { required: true, digits: true, minlength: 5 },
            card_number: { required: true, creditcard: true },
            expiry: { required: true },
            cvv: { required: true, digits: true, minlength: 3, maxlength: 4 }
        },
        messages: {
            full_name: "Please enter your full name (at least 3 characters)",
            email: "Please enter a valid email address",
            card_number: "Please enter a valid credit card number"
        }
    });

    // Function to generate and display summary
    function updateSummary() {
        const data = {};

        // Collect all named inputs from the entire form
        $form.find('input[name], select[name], textarea[name]').each(function() {
            const $field = $(this);
            const name = $field.attr('name');
            const value = $field.val().trim();

            // Group by step or just collect all
            if (value) {
                data[name] = value;
            }
        });

        // Build HTML summary
        let summaryHTML = '<h5 class="mb-3">Review Your Information</h5><dl class="row">';

        // Personal Info
        if (data.full_name || data.email || data.phone) {
            summaryHTML += '<dt class="col-sm-4">Full Name</dt><dd class="col-sm-8">' + (data.full_name || '—') + '</dd>';
            summaryHTML += '<dt class="col-sm-4">Email</dt><dd class="col-sm-8">' + (data.email || '—') + '</dd>';
            summaryHTML += '<dt class="col-sm-4">Phone</dt><dd class="col-sm-8">' + (data.phone || '—') + '</dd>';
        }

        // Address
        if (data.street || data.city || data.zip) {
            summaryHTML += '<dt class="col-sm-4">Street Address</dt><dd class="col-sm-8">' + (data.street || '—') + '</dd>';
            summaryHTML += '<dt class="col-sm-4">City</dt><dd class="col-sm-8">' + (data.city || '—') + '</dd>';
            summaryHTML += '<dt class="col-sm-4">ZIP Code</dt><dd class="col-sm-8">' + (data.zip || '—') + '</dd>';
        }

        // Payment (mask card number for security)
        if (data.card_number) {
            const maskedCard = '•••• •••• •••• ' + data.card_number.slice(-4);
            summaryHTML += '<dt class="col-sm-4">Card Number</dt><dd class="col-sm-8">' + maskedCard + '</dd>';
        }
        if (data.expiry) {
            summaryHTML += '<dt class="col-sm-4">Expiry</dt><dd class="col-sm-8">' + data.expiry + '</dd>';
        }
        if (data.cvv) {
            summaryHTML += '<dt class="col-sm-4">CVV</dt><dd class="col-sm-8">•••</dd>';
        }

        summaryHTML += '</dl>';

        // Add edit note
        summaryHTML += '<p class="text-muted small">Go back to any step to make changes.</p>';

        $summary.html(summaryHTML);
    }

    // Next button
    $('.next-btn').on('click', function() {
        let isValid = true;
        $steps.eq(currentStep).find('input, select, textarea').each(function() {
            if (!$(this).valid()) {
                isValid = false;
            }
        });

        if (isValid && currentStep < $steps.length - 1) {
            $steps.eq(currentStep).removeClass('active');
            currentStep++;
            $steps.eq(currentStep).addClass('active');

            // Update stepper
            $stepItems.eq(currentStep - 1).addClass('completed');
            $stepItems.eq(currentStep).addClass('active');

            // If reaching the last step, update summary
            if (currentStep === $steps.length - 1) {
                updateSummary();
            }
        }
    });

    // Previous button
    $('.prev-btn').on('click', function() {
        if (currentStep > 0) {
            $steps.eq(currentStep).removeClass('active');
            currentStep--;
            $steps.eq(currentStep).addClass('active');

            $stepItems.eq(currentStep + 1).removeClass('active');
            // Don't remove 'completed' if already done
        }

        // Update summary if user returns to Step 4 later
        if (currentStep === $steps.length - 1) {
            updateSummary();
        }
    });

    // Form submit (optional: show final data in console or send via AJAX)
    $form.on('submit', function(e) {
        // e.preventDefault(); // Remove if you want real form post

        if ($form.valid()) {
            console.log('All Form Data:', $form.serializeArray());
            alert('Form submitted successfully!\nCheck console for full data.');
        } else {
            e.preventDefault();
            // Go back to first invalid step (optional enhancement)
        }
    });

    // Initial: if starting on step 4 somehow, show summary
    if (currentStep === $steps.length - 1) {
        updateSummary();
    }
});
</script>
@endpush