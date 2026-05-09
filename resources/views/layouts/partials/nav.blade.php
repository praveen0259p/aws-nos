<nav class="header navbar navbar-expand-lg shadow-sm position-sticky top-0">
    <div class="container-fluid">
        <div class="offcanvas-header justify-content-between">
            <!-- Mobile Search Bar -->
            <form class="searchbox d-block d-lg-none" role="search">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search" aria-label="Search"
                        aria-describedby="search" />
                    <span class="input-group-text border-start border-0" id="search"><i
                            class="bi bi-search"></i></span>
                </div>
            </form>
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-menu" aria-hidden="true">
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                    <path d="M4 6h16"></path>
                </svg>
            </button>
        </div>
        <!-- {{getMenu()}} -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @foreach (getMenu() as $menu)
                        @if ($menu->childrenRecursive->isEmpty())
                            <li class="nav-item">
                                <a href="{{ url($menu->url) }}"
                                    class="nav-link">
                                    {{ $menu->title }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle"
                                    href="javascript:void(0)"
                                    id="menu-{{ $menu->id }}"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ $menu->title }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="menu-{{ $menu->id }}">
                                    @foreach ($menu->childrenRecursive as $child)
                                        @include('layouts.partials.menu-item', ['menu' => $child])
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                    <li class="nav-item d-flex align-items-center">
                        @auth
                            <a href="{{ route('logout') }}" class="loginbtn position-relative me-2">Logout</a>
                        @else
                            <a href="{{ route('login') }}" class="loginbtn position-relative me-2">Login</a>
                        @endauth
                    </li>
                    @php $activePortal = getActiveRegistrationButton(); @endphp
                    @if($activePortal)
                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('register', [$activePortal->year, $activePortal->round]) }}"
                            class="loginbtn position-relative me-2">
                                Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>