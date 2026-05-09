@extends('layouts.app')
@section('title', 'Dashboard')
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
        <div class="row justify-content-center mt-4">
            <div class="col-xl-7 col-lg-7 col-md-6 p-0">
                <div class="form-box bg-white p-4 p-md-5 h-100 rounded-start">
                    <h2 class="text-blue fw-bold mb-4 text-center text-uppercase">Dashboard {{Auth::user()->name}}</h2>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection