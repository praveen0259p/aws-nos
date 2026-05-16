@extends('backend.layouts.app')
@section('title', 'Permissions')
@section('content')

<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Permissions</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('permissions.create')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
        </div>
    </div>
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
    <div class="row py-2">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-data text-center">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Module</th>
                            <th scope="col">Role</th>
                            <th scope="col">View</th>
                            <th scope="col">Create</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $index => $permission)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{$permission->module->module_name}}</td>
                            <td>{{$permission->role->name}}</td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button" type="checkbox"
                                        data-id="{{$permission->id}}" data-permission="can_view" data-status="{{$permission->can_view}}"
                                        {{ $permission->can_view ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button" type="checkbox"
                                        data-id="{{$permission->id}}" data-permission="can_create" data-status="{{$permission->can_create}}"
                                        {{ $permission->can_create ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button" type="checkbox"
                                        data-id="{{$permission->id}}" data-permission="can_edit" data-status="{{$permission->can_edit}}"
                                        {{ $permission->can_edit ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button" type="checkbox"
                                        data-id="{{$permission->id}}" data-permission="can_delete" data-status="{{$permission->can_delete}}"
                                        {{ $permission->can_delete ? 'checked' : '' }}>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    (() => {
        let table = new DataTable('.table-data');
        $(document).on('click', '.status-change', function(e) {
            e.preventDefault();
            let checkbox = $(this);
            let dataAttrs = checkbox.data();
            console.warn(dataAttrs);
            Swal.fire({
                title: 'Change status?',
                text: dataAttrs.status == '1' ?
                    'Do you want to revoke this permission?' : 'Do you want to grant this permission?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: "{{ route('permissions.status') }}",
                    type: "POST",
                    data: {
                        id: dataAttrs.id,
                        permission: dataAttrs.permission,
                        status: dataAttrs.status
                    },
                    beforeSend: function() {
                        checkbox.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            let newStatus = response.status ? 1 : 0;
                            checkbox.prop('checked', response.status == 1);
                            let permission = Object.keys(checkbox.data()).find(key => key.startsWith('can_'));
                            checkbox.data(permission, response.status);
                            checkbox.attr('data-status', newStatus).data('status', newStatus);
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            );
                        }
                    },
                    error: function(err) {
                        let errorMessage = 'Something went wrong!';
                            //console.warn(err.responseJSON);
                            if (err.responseJSON && err.responseJSON.message) {
                                errorMessage = err.responseJSON.message;
                            }
                            Swal.fire(
                                'Error!',
                                errorMessage,
                                'error'
                            );
                    },
                    complete: function() {
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });
    })();
</script>
@endpush