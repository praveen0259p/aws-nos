@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid">
    <div class="row py-2">
        <div class="col-xl-8 col-md-8 col-12">
            <h1>Admin Dashboard</h1>
        </div>
    </div>
    <div class="row justify-content-end py-2">
        <div class="col-12">
            <form class="form-box filter-options">
                <div class="row justify-content-center filters">
                    <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
                        <x-select-input name="status" icon="bi-check2-circle" label="Year"
                            :options="statusoptions()" placeholder="Select Year" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="status" icon="bi-check2-circle" label="Round"
                            :options="statusoptions()" placeholder="Select Round" />
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <x-select-input name="status" icon="bi-check2-circle" label="Reviewer"
                            :options="statusoptions()" placeholder="Select Reviewer" />
                    </div>     
                    <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">   
                        <x-text-input name="title" type="date" placeholder="Select Date Range" label="Select Date Range"
                            icon="bi-card-checklist" autocomplete="Select Date Range" :required="true" />
                    </div>
                    <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 mb-3">        
                        <button type="submit" class="cms-btn rounded-2 px-5">Search</button>
                    </div>    
                </div>
            </form>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-xl-12">
            <div class="d-grid gap-3 dashboard-card-box">
                <div class="dashboard-card bg-white rounded-2 p-3 border border-2 h-100 d-flex flex-column justify-content-center">
                    <p class="fw-semibold text-blue border-bottom border-2 pb-2 text-center text-capitalize">Total Registered Applicants</p>
                    <span class="fw-bold text-blue pt-2 d-block text-center fs-2">12,540</span>
                </div>
                <div class="dashboard-card bg-white rounded-2 p-3 border border-2 h-100 d-flex flex-column justify-content-center">
                    <p class="fw-semibold text-blue border-bottom border-2 pb-2 text-center text-capitalize">Applications Submitted</p>
                    <span class="fw-bold text-blue pt-2 d-block text-center fs-2">8,320</span>
                </div>
                <div class="dashboard-card bg-white rounded-2 p-3 border border-2 h-100 d-flex flex-column justify-content-center">
                    <p class="fw-semibold text-blue border-bottom border-2 pb-2 text-center text-capitalize">Applications Awarded</p>
                    <span class="fw-bold text-success pt-2 d-block text-center fs-2">8,320</span>
                </div>
                <div class="dashboard-card bg-white rounded-2 p-3 border border-2 h-100 d-flex flex-column justify-content-center">
                    <p class="fw-semibold text-blue border-bottom border-2 pb-2 text-center text-capitalize">Applications Rejected</p>
                    <span class="fw-bold text-blue pt-2 d-block text-center fs-2">2,420</span>
                </div>
                <div class="dashboard-card bg-white rounded-2 p-3 border border-2 h-100 d-flex flex-column justify-content-center">
                    <p class="fw-semibold text-blue border-bottom border-2 pb-2 text-center text-capitalize">Applications Pending for Scrutiny</p>
                    <span class="fw-bold text-danger pt-2 d-block text-center fs-2">4,440</span>
                </div>
            </div>
        </div>    
    </div>
    <div class="row py-2">
        <div class="col-xl-6 col-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <h2 class="text-blue text-center fw-bold">Application Scrutiny Status Table</h2>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-data text-center mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Reviewer Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Assigned Applications</th>
                                <th scope="col">Verified Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>L1</td>
                                <td><span class="text-progress fw-bold">In Progress</span></td>
                                <td>150</td>
                                 <td>80</td>
                            </tr>
                            <tr>
                                <td>SO</td>
                                <td><span class="text-success fw-bold">Completed</span></td>
                                <td>115</td>
                                <td>90</td>
                            </tr> 
                            <tr>
                                <td>US</td>
                                <td><span class="text-success fw-bold">Completed</span></td>
                                <td>120</td>
                                <td>88</td>
                            </tr>
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12 mb-3">
            <div class="status-card bg-white rounded-2 h-100">
                <h2 class="text-blue text-center fw-bold">Application Verification Status Table</h2>
                <div class="table-responsive p-3">
                    <table class="table table-bordered table-data text-center mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Reviewer Type</th>
                                <th scope="col">Applications Awarded</th>
                                <th scope="col">Applications Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>L1</td>
                                <td>150</td>
                                <td><span class="text-danger fw-bold">80</span></td>
                            </tr>
                            <tr>
                                <td>SO</td>
                                <td>120</td>
                                <td><span class="text-danger fw-bold">115</span></td>
                            </tr> 
                            <tr>
                                <td>US</td>
                                <td>90</td>
                                <td><span class="text-danger fw-bold">88</span></td>
                            </tr>
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection