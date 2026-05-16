<nav class="side-navigation bg-blue" id="main-menu" aria-label="Side Navigation">
    <ul class="side-menu list-unstyled mb-0 py-2">
        @foreach (getSidebar() as $module)
            @include('backend.layouts.partials.sidebar-item', ['module' => $module])
        @endforeach
    </ul>
</nav>