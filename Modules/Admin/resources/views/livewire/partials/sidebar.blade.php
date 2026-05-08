<aside
    class="flex flex-col h-screen transition-all duration-300
        {{ $theme['background'] }}
        {{ $theme['text'] }}"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    {{-- HEADER --}}
    <div class="h-16 flex items-center justify-center border-b {{ $theme['border'] }}">
        <span x-show="sidebarOpen" class="font-semibold">{{ $titleSidebar }}</span>
        <span x-show="!sidebarOpen">A</span>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        @foreach ($menus as $menu)

            {{-- =========================
                ✅ FILTER PERMISSION ROOT
            ========================== --}}
            @php
                // Lọc children theo permission
                $children = collect($menu['children'] ?? [])
                    ->filter(fn($child) => empty($child['can']) || auth()->user()->can($child['can']))
                    ->values();

                $hasChildren = !empty($menu['has_children']) && $children->isNotEmpty();

                // Check quyền menu cha
                $canAccessMenu = empty($menu['can']) || auth()->user()->can($menu['can']);
            @endphp

            {{-- ❌ Không có quyền + không có children hợp lệ --}}
            @if(!$canAccessMenu && !$hasChildren)
                @continue
            @endif

            {{-- =========================
                ✅ ACTIVE LOGIC
            ========================== --}}
            @php
                $isActive = false;

                $current = trim(request()->path(), '/');
                $pattern = trim($menu['url'] ?? '', '/');

                if (!empty($pattern)) {
                    $isActive = $current === $pattern;

                    if (!$isActive && $pattern !== 'admin') {
                        $isActive = str_starts_with($current, $pattern . '/');
                    }
                }

                if (!$isActive && $hasChildren) {
                    $isActive = $children->contains(function ($child) use ($current) {
                        $childPattern = trim($child['url'] ?? '', '/');
                        return $current === $childPattern || str_starts_with($current, $childPattern . '/');
                    });
                }
            @endphp


            {{-- =========================
                ✅ SINGLE MENU
            ========================== --}}
            @if (!$hasChildren && $canAccessMenu)

                <a href="{{ !empty($menu['url']) ? url($menu['url']) : '#' }}"
                   class="flex items-center px-3 py-2 rounded-lg transition
                   {{ $isActive ? $theme['active_bg'].' '.$theme['active_text'] : $theme['hover'] }}">

                    @if (!empty($menu['icon']))
                        <x-icon name="{{ $menu['icon'] }}"
                            class="w-5 h-5
                            {{ $isActive ? $theme['icon_active'] : $theme['icon_inactive'] }}" />
                    @endif

                    <span x-show="sidebarOpen" class="ml-3 text-sm">
                        {{ $menu['name'] }}
                    </span>
                </a>

            {{-- =========================
                ✅ GROUP MENU
            ========================== --}}
            @elseif ($hasChildren)

                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">

                    <button @click="sidebarOpen ? open = !open : sidebarOpen = true"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition
                        {{ $isActive ? $theme['active_bg'].' '.$theme['active_text'] : $theme['hover'] }}">

                        <div class="flex items-center">

                            @if (!empty($menu['icon']))
                                <x-icon name="{{ $menu['icon'] }}"
                                    class="w-5 h-5
                                    {{ $isActive ? $theme['icon_active'] : $theme['icon_inactive'] }}" />
                            @endif

                            <span x-show="sidebarOpen" class="ml-3 text-sm">
                                {{ $menu['name'] }}
                            </span>
                        </div>

                        <svg x-show="sidebarOpen"
                            :class="open ? 'rotate-90' : ''"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M6 6L14 10L6 14V6Z" />
                        </svg>
                    </button>

                    {{-- CHILDREN --}}
                    <div x-show="open && sidebarOpen" x-collapse class="ml-6 mt-1 space-y-1">

                        @foreach ($children as $child)

                            @php
                                $childActive = request()->is(ltrim($child['url'], '/') . '*');
                            @endphp

                            <a href="{{ url($child['url']) }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                               {{ $childActive
                                    ? $theme['child_active_bg'].' '.$theme['child_active_text']
                                    : $theme['child_text'].' '.$theme['child_hover'] }}">

                                <svg class="w-3.5 h-3.5 opacity-70" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 6L14 10L6 14V6Z" />
                                </svg>

                                <span>{{ $child['name'] }}</span>
                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        @endforeach

    </nav>

    {{-- USER --}}
    <div class="border-t border-gray-800 p-4 transition-all duration-300">
        <div class="flex items-center" :class="!sidebarOpen ? 'justify-center' : ''">

            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>

            <div x-show="sidebarOpen" class="ml-3 whitespace-nowrap overflow-hidden">
                <p class="font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">View Profile</p>
            </div>

        </div>
    </div>

</aside>
