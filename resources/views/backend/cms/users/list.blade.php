@extends('backend.layouts.app')
@section('title', 'Users')
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>User Management</h1>
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
                            <th scope="col">Role</th>
                            <th scope="col">Count</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roleWiseUsers as $index => $user)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $user->name ?? '—' }}</td>
                            <td><a  href="{{ route('users.roletype', encrypt($user->role_id)) }}"><span class="bg-blue rounded-2 badge fs-6" role="button">
                                {{ $user->users_count ?? '—' }}</span></a>
                            </td>
                            <td>
                                <ul class="d-flex align-items-center justify-content-center gap-3">
                                    <li>
                                        <a href="{{ route('users.roletype', encrypt($user->role_id)) }}" class="fs-5 view">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
    })();
</script>

@endpush