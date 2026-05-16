@extends('backend.layouts.app')
@section('title', 'Edit Modules')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Edit Modules</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('modules.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="modules-form" action="{{route('modules.updatemodule')}}" method="post" class="form-box">
                @csrf
                <div class="row">
                    <x-text-input
                        name="id"
                        type="hidden"
                        value="{{decrypt($id)}}" />
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="moduleid"
                            type="number"
                            value="{{$module->module_id}}"
                            placeholder="Enter Module Id"
                            label="Enter Module Id"
                            icon="bi-list-ol"
                            autocomplete="Enter Module Id"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="parent"
                            icon="bi bi-diagram-2"
                            selected="{{$module->parent_id}}"
                            label="Choose parent"
                            :options="$modules"
                            placeholder="Choose parent"
                            :required="false" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="name"
                            type="text"
                            value="{{$module->module_name}}"
                            placeholder="Enter Module Name"
                            label="Enter Module Name"
                            icon="bi-textarea-t"
                            autocomplete="Enter Module Name"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="url"
                            type="text"
                            value="{{$module->page_url}}"
                            placeholder="Enter Page url"
                            label="Enter Page url"
                            icon="bi-link"
                            autocomplete="Enter Page url"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="position"
                            type="number"
                            value="{{$module->position}}"
                            placeholder="Enter Position"
                            label="Enter Position"
                            icon="bi-list-ol"
                            autocomplete="Enter Position"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="icon"
                            type="text"
                            value="{{$module->icon_name}}"
                            placeholder="Enter Icon"
                            label="Enter Icon"
                            icon="bi-ui-checks-grid"
                            autocomplete="Enter Icon"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="status"
                            icon="bi-check2-circle"
                            label="Select Status"
                            selected="{{$module->active}}"
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
        $("#modules-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                moduleid: {
                    required: true,
                    digits: true
                },
                name: {
                    required: true,
                    lettersonly: true
                },
                url: {
                    required: true
                },
                position: {
                    required: true,
                    digits: true
                },
                icon: {
                    required: true
                },
                status: {
                    required: true,
                    digits: true,
                    range: [0, 1]
                }
            },
            messages: {
                moduleid: {
                    required: "Please enter module id",
                    digits: "Module ID must be a number"
                },
                name: {
                    required: "Enter module name",
                    lettersonly: "Only letters are allowed"
                },
                url: {
                    required: "Enter module URL"
                },
                position: {
                    required: "Enter module position",
                    digits: "Position must be a number"
                },
                icon: {
                    required: "Enter module icon"
                },
                status: {
                    required: "Please choose status",
                    digits: "Status must be numeric",
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