@extends('backend.layouts.app')
@section('title', 'Ministers')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Ministers Management</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <a href="{{route('ministers.create')}}" class="theme-btn rounded-2 float-end"><i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
        </div>
    </div>
    <!-- <div class="row justify-content-between py-2">
                    <div class="col-xl-6 col-md-5 col-12 mb-md-0 mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="showcount" class="col-form-label">Show</label>
                            </div>
                            <div class="col-auto">
                                <select class="form-select w-100 theme-border" id="showcount"
                                    aria-label="Default select example">
                                    <option value="1">1000</option>
                                    <option value="2">20</option>
                                    <option value="3">50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-12">
                        <div class="input-group mb-3">
                            <input type="search" class="form-control border-end-0 theme-border" placeholder="Search"
                                aria-label="Search" aria-describedby="search">
                            <span class="input-group-text bg-transparent theme-border" id="search"><i class="bi bi-search"
                                    aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div> -->
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
                            <th scope="col">Image</th>
                            <th scope="col">Size</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Is Active</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ministers as $index => $minister)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $minister->name ?? '—' }}</td>
                            <td>@if(optional($minister->asset)->url)
                                <img src="{{ asset($minister->asset->url) }}" alt="{{$minister->title}}" style="width: 200px; height: auto;">
                                @else
                                —
                                @endif
                            </td>
                            <td>{{ formatBytes($minister->asset->size) ?? '—' }}</td>
                            <td>
                                {{ optional($minister->created_at)->format('d-M-Y, h:i A') }}
                            </td>
                            <td>
                                {{ optional($minister->user)->full_name ?? '—' }}
                                ({{ optional($minister->user->role)->name ?? '—' }})
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer " role="button"
                                        type="checkbox" data-id="{{$minister->id}}" data-active="{{$minister->active}}"
                                        {{ $minister->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <ul class="d-flex align-items-center justify-content-center gap-3">
                                    <li>
                                        <a href="{{ route('ministers.edit', encrypt($minister->id)) }}" class="fs-5 edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="{{asset($minister->asset->url)}}" class="fs-5 view">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <button type="submit" class="btn p-0 fs-5 delete delete-btn" data-id="{{$minister->id}}">
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
                    url: "{{ route('ministers.status') }}",
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
                        url: "{{ route('ministers.delete') }}",
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