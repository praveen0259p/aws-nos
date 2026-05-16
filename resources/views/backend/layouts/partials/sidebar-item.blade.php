<li class="menu-item {{ $module->isActive() ? 'active' : '' }}">
    @if ($module->children->isEmpty())
        <a href="{{ url($module->page_url) }}" class="menu-link">
            <i class="bi {{ $module->icon_name }}"></i>
            <span>{{ $module->module_name }}</span>
        </a>
    @else
        <button class="menu-link submenu-toggle"
            aria-expanded="{{ $module->isActive() ? 'true' : 'false' }}"
            aria-controls="menu-{{ $module->module_id }}">
            <i class="bi {{ $module->icon_name }}"></i>
            <span>{{ $module->module_name }}</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </button>

        <ul class="submenu list-unstyled {{ $module->isActive() ? 'show' : '' }}" id="menu-{{ $module->module_id }}">
            @foreach ($module->children as $child)
                @include('backend.layouts.partials.sidebar-item', ['module' => $child])
            @endforeach
        </ul>
    @endif
</li>
