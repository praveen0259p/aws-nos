@extends('backend.layouts.app')
@section('title', 'Edit Document')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Edit Document</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('documents.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="document-form" action="{{route('documents.updatedocument')}}" method="post" class="form-box" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <x-text-input
                        name="id"
                        type="hidden"
                        value="{{$document->id}}" />
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="title"
                            type="text"
                            value="{{$document->title}}"
                            placeholder="Enter Your Title"
                            label="Enter Your Title"
                            icon="bi-card-checklist"
                            autocomplete="Enter Your Title"
                            :required="true" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="menu"
                            icon="bi-check2-circle"
                            label="Select Menu"
                            :options="$type"
                            selected="{{$document->parent_menu_id}}"
                            placeholder="Select Menu" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-text-input
                            name="file"
                            type="file"
                            placeholder="Upload Document"
                            label="Upload Document"
                            icon="bi-upload"
                            autocomplete="Upload Document"
                            :required="false"
                            accept="application/pdf" />
                        <small class="text-black mt-2 d-block">(Only Pdf files allowed, Max Size:5MB)</small>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <x-select-input
                            name="status"
                            selected="{{$document->active}}"
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
                    alphanumericSpecial: true,
                },
                file: {
                    required: false,
                    pdfAndSize: true
                },
                menu: {
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
                    required: "Enter Document Title",
                },
                menu: {
                    required: "Please Choose Menu",
                    digits: "Invalid Menu Choosen"
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