@extends('backend.layouts.app')
@section('title', 'Users Details')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>{{$role->name}} Users</h1>
        </div>
        <div class="col-xl-4 col-md-4 col-12">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <a href="{{route('users.create')}}" class="theme-btn rounded-2 float-end">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> CREATE</a>
                <a href="{{route('users.list')}}" class="theme-btn rounded-2 float-end">
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
                            <th scope="col">Reg.No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Date of Birth</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Email</th>
                            <th scope="col">Category</th>
                            <th scope="col">Is Active</th>
                            <th scope="col">Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $user->regno ?? 'NA' }}</td>
                            <td>{{ $user->full_name ?? 'NA' }}</td>
                            <td>{{ $user->father_name ?? 'NA' }}</td>
                            <td>{{ genderOptions()[$user->gender] }}</td>
                            <td>{{ optional($user->dob)->format('d-M-Y') ?? 'NA' }}</td>
                            <td>{{ $user->mobile ?? '—' }}</td>
                            <td>{{ $user->email ?? '—' }}</td>
                            <td>{{ categoryOptions()[$user->category] ?? '—' }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-change pointer" role="button"
                                        type="checkbox" data-id="{{$user->id}}" data-active="{{$user->active}}"
                                        {{ $user->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>{{ $user->created_at->format('d-M-Y') }}</td>
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
            let id = checkbox.data('id');
            let active = checkbox.data('active');
            Swal.fire({
                title: 'Change status?',
                text: active == 1 ?
                    'Do you want to deactivate this User?' : 'Do you want to activate this User?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: "{{ route('users.status') }}",
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