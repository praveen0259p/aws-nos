@extends('backend.layouts.app')
@section('title', 'Create Role')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Create Role</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('role.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="role-form" action="{{route('role.save')}}" method="post" class="form-box" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="roleid"
                            type="number"
                            placeholder="Enter Role Id"
                            label="Enter Role Id"
                            icon="bi-card-checklist"
                            autocomplete="Enter Role Id"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="role"
                            type="text"
                            placeholder="Enter Role Name"
                            label="Enter Role Name"
                            icon="bi-card-checklist"
                            autocomplete="Enter Role Name"
                            :required="true" />
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

<script>
    (()=>{
        $("#role-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                roleid: {
                    required: true,
                    digits: true
                },
                role: {
                    required: true,
                    lettersonly: true,
                },
                status: {
                    required: true,
                    digits: true,
                    range: [0, 1]
                }
            },
            messages: {
                role: {
                    required: "Enter Role name",
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
@push('scripts')