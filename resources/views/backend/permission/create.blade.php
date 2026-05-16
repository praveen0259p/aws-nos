@extends('backend.layouts.app')
@section('title', 'Assign Permission')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Assign Permission</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('permissions.list')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-caret-left-fill"
                    aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-between py-2">
        <div class="col-xl-12 col-12 mb-md-0 mb-3">
            <form id="permission-form" action="{{route('permissions.save')}}" method="post" class="form-box" enctype="multipart/form-data">
                @csrf
                <div class="row">
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
                            name="modules"
                            icon="bi-check2-circle"
                            label="Select Module"
                            :options="$modules"
                            placeholder="Select Module" />
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-data text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">View</th>
                                        <th scope="col">Create</th>
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <input name="can_view" class="form-check-input status-change pointer" role="button" type="checkbox">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <input name="can_create" class="form-check-input status-change pointer" role="button" type="checkbox">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <input name="can_edit" class="form-check-input status-change pointer" role="button" type="checkbox">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <input name="can_delete" class="form-check-input status-change pointer" role="button" type="checkbox">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
    (() => {
        $("#permission-form").validate({
            ignore: [],
            errorClass: 'text-danger',
            errorElement: 'small',
            rules: {
                role: {
                    required: true,
                    digits: true
                },
                modules: {
                    required: true,
                    digits: true
                },
            },
            messages: {
                role: {
                    required: "Select Role",
                },
                modules: {
                    required: "Select Modules",
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