@if ($menu->childrenRecursive->isEmpty())
    <li>
        <a class="dropdown-item" href="{{ $menu->is_pdf_menu == 1 ? asset($menu->pdf_url) : $menu->url }}" target="{{ $menu->target ?? '_self' }}">
            {{ $menu->title }}
        </a>
    </li>
@else
    <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="javascript:void(0)">
            {{ $menu->title }}
        </a>
        <ul class="dropdown-menu">
            @foreach ($menu->childrenRecursive as $child)
                @include('layouts.partials.menu-item', ['menu' => $child])
            @endforeach
        </ul>
    </li>
@endif
