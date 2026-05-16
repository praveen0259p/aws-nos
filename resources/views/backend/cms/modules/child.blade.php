@extends('backend.layouts.app')
@section('title', 'Modules')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Module Management</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{route('modules.create')}}" class="theme-btn rounded-2 float-end">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
                <a href="{{route('modules.list')}}" class="theme-btn rounded-2 float-end">
                    <i class="bi bi-caret-left-fill" aria-hidden="true"></i> Back</a>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row py-2">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-data">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Module Name</th>
                            <th scope="col">Child Module</th>
                            <th scope="col">Page Url</th>
                            <th scope="col">Position</th>
                            <th scope="col">Icon</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modules as $index => $module)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $module->module_name ?? '—' }}</td>
                            <td>
                                @if($module->children->count())
                                <a href="{{ route('menus.child.list',encrypt($module->id)) }}">
                                    <span class="bg-blue rounded-2 badge fs-6">{{ $module->children->count() ?? 0 }}</span>
                                </a>
                                @else
                                <span class="bg-blue rounded-2 badge fs-6">{{ $module->children->count() ?? '—' }}</span>
                                @endif
                            </td>
                            <td>{{ $module->page_url ?? '—' }}</td>
                            <td>{{ $module->position ?? '—' }}</td>
                            <td>{{ $module->icon_name ?? '—' }}</td>
                            <td>
                                {{ optional($module->created_at)->format('d-M-Y, h:i A') }}
                            </td>
                            <td>
                                {{ optional($module->user)->full_name ?? '—' }}
                                ({{ optional($module->user->role)->name ?? '—' }})
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer " role="button"
                                        type="checkbox" data-id="{{$module->id}}" data-active="{{$module->active}}"
                                        {{ $module->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <ul class="d-flex align-items-center justify-content-center gap-3">
                                    <li>
                                        <a href="{{ route('modules.edit', encrypt($module->id)) }}" class="fs-5 edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <button class="btn p-0 fs-5 delete delete-btn" data-id="{{$module->module_id}}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </li> -->
                                </ul>
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
    (()=>{

        let table = new DataTable('.table-data');
        $(document).on('click', '.status-change', function(e) {
            e.preventDefault();
            let checkbox = $(this);
            let id = checkbox.data('id');
            let active = checkbox.data('active');
            Swal.fire({
                title: 'Change status?',
                text: active == 1 ?
                    'Do you want to deactivate this item?' : 'Do you want to activate this item?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: "{{ route('modules.status') }}",
                    type: "POST",
                    data: {
                        id: id,
                        status: active == 1 ? 0 : 1
                    },
                    beforeSend: function() {
                        checkbox.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            checkbox.data('active', response.status);
                            checkbox.prop('checked', response.status == 1);
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            );
                        }
                    },
                    error: function(res) {
                        Swal.fire(
                            'Error!',
                            res.responseJSON.message,
                            'error'
                        );
                    },
                    complete: function() {
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let button = $(this);
            let id = button.data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('modules.delete') }}",
                        type: "POST",
                        data: {
                            id: id,
                        },
                        beforeSend: function() {
                            button.prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                button.closest('tr').remove();
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(err) {
                            let errorMessage = 'Something went wrong!';
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
                            button.prop('disabled', false);
                        }
                    });
                }
            });
        });

    })();
</script>

@endpush