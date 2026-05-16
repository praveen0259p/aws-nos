@extends('backend.layouts.app')
@section('title', 'Access Denied')
@section('content')
<div class="d-flex align-items-center justify-content-center overflow-hidden" style="height: calc(100vh - 70px);" id="b-homedb">
    <div class="container-fluid">
        <div class="text-center d-flex flex-column align-items-center">
            <h1 class="mb-3 text-blue fw-bold">
                <i class="bi bi-lock" aria-hidden="true"></i>
                You’re not authorized to {{$action ?? ''}}  {{$module ?? ''}}
            </h1>

            <p class="mb-2">
                Hello <strong>{{ Auth::user()->full_name }} ({{ Auth::user()->role->name }})</strong>,
                It looks like you don’t have permission to <span class="fw-bold">{{$action ?? ''}} {{$module ?? ''}}</span> section right now.
            </p>

            <p class="mb-4">
                If this seems unexpected, please reach out to your administrator to have it reviewed.
            </p>

            <p class="text-muted mb-4">
                Redirecting you back in <strong><span id="counter">5</span></strong> seconds…
            </p>

            <a class="theme-btn rounded-2" href="{{ url()->previous() }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Go Back
            </a>
        </div>
    </div>
</div>

<script>
    let seconds = 20;
    const counter = document.getElementById('counter');
    const timer = setInterval(() => {
        seconds--;
        counter.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            window.history.back();
        }
    }, 1000);
</script>
@endsection
