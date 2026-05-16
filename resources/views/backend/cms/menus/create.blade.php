@extends('backend.layouts.app')
@section('title', 'Create Menu')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Create Menu</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('menus.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="document-form" action="{{route('menus.savemenu')}}" method="post" class="form-box" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="title"
                            type="text"
                            placeholder="Enter Menu Title"
                            label="Enter Menu Title"
                            icon="bi-card-checklist"
                            autocomplete="Enter Menu Title"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="url"
                            type="text"
                            placeholder="Enter Menu url"
                            label="Enter Menu url"
                            icon="bi-card-checklist"
                            autocomplete="Enter Menu url"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="parent"
                            icon="bi-check2-circle"
                            label="Choose parent"
                            :options="$menu"
                            placeholder="Choose parent"
                            :required="false" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="target"
                            icon="bi-check2-circle"
                            label="Choose Target"
                            :options="targettype()"
                            placeholder="Choose Target" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="page_type"
                            icon="bi-check2-circle"
                            label="Choose Page Type"
                            :options="pagetype()"
                            placeholder="Choose Page Type" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="menu_type"
                            label="Select Menu Type"
                            :options="\App\Models\MenuItem::menuTypeOptions()"
                            placeholder="Select Menu Type" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="ordering"
                            type="text"
                            placeholder="Enter Ordering"
                            label="Enter Ordering"
                            icon="bi-card-checklist"
                            autocomplete="Enter Ordering"
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
        $("#document-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                title: {
                    required: true,
                    lettersonly: true,
                },
                file: {
                    required: true,
                    imageAndSize: true
                },
                ordering: {
                    required: true,
                    digits: true
                },
                status: {
                    required: true,
                    digits: true,
                    range: [0, 1]
                }
            },
            messages: {
                title: {
                    required: "Enter Banner Title",
                },
                file: {
                    required: "Banner Image is required",
                },
                ordering: {
                    required: "Enter Priority Ordering",
                    digits: "Ordering must be a number"
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