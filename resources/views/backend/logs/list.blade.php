@extends('backend.layouts.app')
@section('title', 'Logs')
@section('content')

<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Logs Management</h1>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-data">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Reg No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Ip Address</th>
                            <th scope="col">Browser</th>
                            <th scope="col">Platform</th>
                            <th scope="col">Logged In </th>
                            <th scope="col">Logged Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $index => $history)
                        <tr>
                            <th scope="row" class="text-end">{{ $index + 1 }}</th>
                            <td>{{ $history->regno ?? 'NA' }}</td>
                            <td>{{ $history->user->full_name ?? '—' }}</td>
                            <td>{{ $history->user->role->name ?? '—' }}</td>
                            <td>{{ $history->ip_address ?? '—' }}</td>
                            <td>{{ $history->browser ?? '—' }}</td>
                            <td>{{ $history->platform ?? '—' }}</td>
                            <td class="text-center">
                                {{ optional($history->logged_in_at)->format('d-M-Y, h:i A') ?? '—' }}
                            </td>
                            <td>
                                {{ optional($history->logged_out_at)->format('d-M-Y, h:i A') ?? '—' }}
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