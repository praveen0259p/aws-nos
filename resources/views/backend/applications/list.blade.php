@extends('backend.layouts.app')
@section('title', 'Application Form')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Applications Management</h1>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered  table-data">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Application No</th>
                            <th scope="col">Window</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Father's Name</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Category</th>
                            <th scope="col">Status</th>
                            <th scope="col">Submitted On</th>
                            <th scope="col">Actions / History</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $index => $app)
                            <tr>
                                <th scope="row" class="text-end">{{ $index + 1 }}</th>
                                <td>{{ $app->application_number }}</td>
                                <td>{{ $app->window->title ?? '—' }}</td>
                                <td>{{ $app->personalInfo->applicant_name ?? 'NA' }}</td>
                                <td>{{ $app->personalInfo->father_name ?? 'NA' }}</td>
                                <td>{{ $app->user->category ?? 'NA' }}</td>
                                <td>{{ genderOptions()[$app->personalInfo->gender] ?? 'NA' }}</td>
                                <td>
                                    @if($app->application_status == 0)
                                        <span class="badge bg-warning">Drafetd Application</span>
                                    @elseif($app->application_status == 1)
                                        <span class="badge bg-success">Final Submit</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $app->submit_date ? $app->submit_date->format('d M Y H:i') : 'Not submitted' }}</td>
                                <td>
                                    <ul class="d-flex align-items-center justify-content-center gap-2 mb-0">
                                        <li>
                                            <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#history-{{ $app->id }}">
                                                History
                                            </button>
                                        </li>
                                    </ul>

                                    {{-- Collapsible History Table --}}
                                    <div class="collapse mt-2" id="history-{{ $app->id }}">
                                        @if($app->history->count())
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Field</th>
                                                        <th>Old Value</th>
                                                        <th>New Value</th>
                                                        <th>Changed By</th>
                                                        <th>Changed At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($app->history as $history)
                                                        <tr>
                                                            <td>{{ $history->field_name }}</td>
                                                            <td>{{ $history->old_value }}</td>
                                                            <td>{{ $history->new_value }}</td>
                                                            <td>{{ $history->user->name ?? 'System' }}</td>
                                                            <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <span>No history</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')

<!-- <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    (() => {
        //let table = new DataTable('.table-data');
        $('.table-data').DataTable({
            dom: '<"row mb-2"<"col-md-6"B><"col-md-6 text-end"f>>rt<"row mt-2"<"col-md-6"i><"col-md-6"p>>',
            buttons: ['excelHtml5', 'pdfHtml5'],
            order: [[0, 'asc']]
        });
    })();
</script>
@endpush