@include('layouts.partials.header')
@include('layouts.partials.topbar')
@include('layouts.partials.nav')
<main class="overflow-x-hidden">
    @yield('content')
</main>
@include('layouts.partials.footer')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@stack('scripts')
</body>
</html>