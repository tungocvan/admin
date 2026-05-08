@php
    $menus = config('admin_menu', []);
    $user = auth()->user();

    // Hàm kiểm tra active cho tree menu (nếu menu có children)
    function isActiveMenu($menu)
    {
        if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if (request()->routeIs($child['route'])) {
                    return true; // Chỉ mở nếu route CON khớp
                }
            }
        }
        return false; // Không mở nếu không phải route con
    }

@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <!-- SIDEBAR BRAND -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link logo-switch">
            <img src="/assets/img/AdminLTELogo.png" alt="Admin Logo Small"
                class="brand-image-xl logo-xs opacity-75 shadow">

            <img src="/assets/img/AdminLTEFullLogo.png" alt="Admin Logo Large" class="brand-image-xs logo-xl opacity-75">
        </a>
    </div>

    <!-- SIDEBAR WRAPPER -->
    <div class="sidebar-wrapper" data-overlayscrollbars="host">
        <div class="" data-overlayscrollbars-viewport="scrollbarHidden overflowXHidden overflowYHidden">

            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                    data-accordion="false">

                    @foreach ($menus as $menu)

                        {{-- Kiểm tra quyền --}}
                        @if ($user && ($user->hasRole('admin') || $user->can($menu['permission'])))
                            {{-- Nếu có menu con => TREEVIEW --}}
                            @if (!empty($menu['children']))
                                @php
                                    $isOpen = isActiveMenu($menu) ? 'menu-open' : '';
                                    $isActive = isActiveMenu($menu) ? 'active' : '';
                                @endphp

                                <li class="nav-item {{ $isOpen }}">
                                    <a href="#" class="nav-link {{ $isActive }}">
                                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                                        <p>
                                            {{ $menu['label'] }}
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>

                                    <!-- Submenu -->
                                    <ul class="nav nav-treeview">
                                        @foreach ($menu['children'] as $child)
                                            {{-- Kiểm tra quyền submenu --}}
                                            @if ($user->can($child['permission']) || $user->hasRole('admin'))
                                                <li class="nav-item">
                                                    <a href="{{ route($child['route']) }}"
                                                        class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <p>{{ $child['label'] }}</p>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                {{-- MENU ĐƠN --}}
                                <li class="nav-item">
                                    <a href="{{ route($menu['route']) }}"
                                        class="nav-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                                        <p>{{ $menu['label'] }}</p>
                                    </a>
                                </li>
                            @endif
                        @endif

                    @endforeach

                </ul>
            </nav>

        </div>
    </div>
</aside>
