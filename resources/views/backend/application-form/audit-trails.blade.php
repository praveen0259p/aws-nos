@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Audit Trail & Logs</h1>
        </div>
    </div>
    <div class="row justify-content-end py-2">
        <div class="col-12">
            <form class="form-box filter-options">
                <div class="row justify-content-end filters">
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">   
                        <x-text-input name="title" type="date" placeholder="Select Date Range" label="Select Date Range"
                            icon="bi-card-checklist" autocomplete="Select Date Range" :required="true" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="action" icon="bi-check2-circle" label="Round"
                            :options="statusoptions()" placeholder="Select Action Type" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <button type="submit" class="cms-btn rounded-2 px-5">Search</button>
                    </div>    
                </div>
            </form>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-data text-center">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Application ID</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Timestamp</th>
                            <th scope="col">Action Type</th>
                            <th scope="col">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>App-1023</td>
                            <td>USER1234</td>
                            <td>02/10/2024 , 14:32:10</td>
                            <td>Application Submitted</td>
                            <td>
                                <a target="_blank" href="#" class="fs-5 view">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
@endpush