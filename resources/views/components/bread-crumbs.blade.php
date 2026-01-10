<section class="page-banner position-relative">
    <div class="container-fluid position-relative z-1">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ $homeUrl ?? '#' }}" class="text-white">{{ $homeLabel ?? 'Home' }}</a></li>
                    <span class="breadcrumb-sep text-white mx-2" aria-hidden="true"> /</span>
                    <li class="breadcrumb-item active text-white text-capitalize" aria-current="page">{{ $currentPage }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <h1 class="text-white text-capitalize">{{ $currentPage }}</h1>
        </div>
        <!-- @if(!empty($menuItems))
        <div class="menu-container bg-blue rounded-3 py-3 px-4 shadow-lg mt-5 position-relative mx-1">
            <button class="scroll-btn left-btn position-absolute" aria-label="Scroll left">
                <i class="bi bi-chevron-left text-white"></i>
            </button>
            <ul id="scrollMenu" class="menu-scroll d-flex align-items-center gap-4 text-white m-0 p-0">
                @foreach($menuItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="menu-link {{ $item['active'] ?? '' }}">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
            <button class="scroll-btn right-btn position-absolute" aria-label="Scroll right">
                <i class="bi bi-chevron-right text-white"></i>
            </button>
        </div>
        @endif -->
    </div>
</section>
