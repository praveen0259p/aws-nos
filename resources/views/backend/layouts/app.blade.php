@include('backend.layouts.partials.header')
@include('backend.layouts.partials.topbar')
<div class="wrapper position-relative">
    @include('backend.layouts.partials.sidebar')
    <div class="content-box p-3">
        <div class="data-box rounded-3 shadow-sm p-3">
            <main id="maincontent">
                @yield('content')
            </main>
        </div>
    </div>
</div>
@include('backend.layouts.partials.footer')