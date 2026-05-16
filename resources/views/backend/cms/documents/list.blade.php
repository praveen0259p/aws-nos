@extends('backend.layouts.app')
@section('title', 'Documents')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Documents Management</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('documents.create')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
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
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Size</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Is Active</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $index => $document)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $document->title ?? '—' }}</td>
                            <td>{{ $document->menuItem->title ?? '—' }}</td>
                            <td>{{ optional($document->asset)->size ? formatBytes($document->asset->size) : '—' }}</td>
                            <td>
                                {{ optional($document->created_at)->format('d-M-Y, h:i A') }}
                            </td>
                            <td>
                                {{ optional($document->user)->full_name ?? '—' }}
                                ({{ optional($document->user->role)->name ?? '—' }})
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button"
                                        type="checkbox" data-id="{{$document->id}}" data-active="{{$document->active}}"
                                        {{ $document->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <ul class="d-flex align-items-center justify-content-center gap-3">
                                    <li>
                                        <a href="{{ route('documents.edit', encrypt($document->id)) }}" class="fs-5 edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="{{ optional($document->asset)->url ? asset($document->asset->url) : '#' }}" class="fs-5 view">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <button type="submit" class="btn p-0 fs-5 delete delete-btn" data-id="{{$document->id}}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </li>
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
                    url: "{{ route('documents.status') }}",
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
                    error: function(err) {
                        let errorMessage = 'Something went wrong!';
                            console.warn(err.responseJSON);
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
                        url: "{{ route('documents.delete') }}",
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