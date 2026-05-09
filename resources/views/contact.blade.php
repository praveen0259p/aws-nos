@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')
<x-bread-crumbs
    current-page="{{ Route::currentRouteName() }}"
    :menu-items="[
        ['label' => 'Reports', 'url' => '#', 'active' => 'active'],
        ['label' => 'Orders and Notices', 'url' => '#'],
        ['label' => 'Publications', 'url' => '#']
    ]" />
<section class="py-5 bg-grey-light">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-11 col-12 mt-3">
                <div class="form-box bg-white p-4 p-md-5 h-100 rounded">
                    <h2 class="text-blue fw-bold mb-5 text-center text-uppercase">For Scheme Related queries Onlyyyy</h2>
                    <ul class="contact-details">
                        <li><i class="bi bi-person-fill" aria-hidden="true"></i> <span><b>Shri Yogesh Taneja</b> <br>Under Secretary</span></li>
                        <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> Shastri Bhawan Dr. Rajendra Prasad Road, New Delhi-110001</li>
                        <li><i class="bi bi-telephone-inbound-fill" aria-hidden="true"></i> 011 23384023 (All working days 3PM to 5PM)</li>
                        <li><i class="bi bi-envelope-at-fill" aria-hidden="true"></i> so-nos-msje[at]gov[dot]in</li>
                        <li><i class="bi bi-person-lines-fill" aria-hidden="true"></i> <span><b>For any technical support please email</b> <br />support-nha[at]supportgov[dot]in</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection