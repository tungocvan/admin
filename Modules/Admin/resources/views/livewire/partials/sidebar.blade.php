<aside
    class="flex flex-col h-full transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]
    {{ $theme['background'] }} {{ $theme['text'] }}"
    :class="sidebarOpen ? 'w-64' : 'w-20'"
>

    {{-- HEADER --}}
    <div class="h-16 flex items-center justify-between px-4 border-b {{ $theme['border'] }}">

        {{-- LOGO --}}
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-500 text-white font-bold">
                A
            </div>

            <span x-show="sidebarOpen" class="font-semibold whitespace-nowrap">
                {{ $titleSidebar }}
            </span>
        </div>

        {{-- TOGGLE --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
        >
            <svg
                :class="sidebarOpen ? 'rotate-180' : ''"
                class="w-5 h-5 transition-transform"
                fill="none"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
        </button>

    </div>

    {{-- MENU CONTAINER --}}
    <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-1">

        @foreach ($menus as $menu)

            @php
                $children = collect($menu['children'] ?? [])
                    ->filter(fn($child) => empty($child['can']) || auth()->user()->can($child['can']))
                    ->values();

                $hasChildren = !empty($menu['has_children']) && $children->isNotEmpty();

                $canAccessMenu = empty($menu['can']) || auth()->user()->can($menu['can']);
            @endphp

            @if(!$canAccessMenu && !$hasChildren)
                @continue
            @endif

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

            {{-- SINGLE MENU --}}
            @if (!$hasChildren && $canAccessMenu)

                <a href="{{ !empty($menu['url']) ? url($menu['url']) : '#' }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-200
                   {{ $isActive 
                        ? 'bg-indigo-50 text-indigo-600 shadow-sm' 
                        : 'text-gray-600 hover:bg-gray-100 active:scale-[0.98]' }}">

                    @if (!empty($menu['icon']))
                        <x-icon
                            name="{{ $menu['icon'] }}"
                            class="w-5 h-5 flex-shrink-0
                            {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}"
                        />
                    @endif

                    <span x-show="sidebarOpen" class="whitespace-nowrap">
                        {{ $menu['name'] }}
                    </span>
                </a>

            {{-- GROUP MENU --}}
            @elseif ($hasChildren)

                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">

                    <button
                        @click="sidebarOpen ? open = !open : sidebarOpen = true"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all duration-200
                        {{ $isActive 
                            ? 'bg-indigo-50 text-indigo-600 shadow-sm' 
                            : 'text-gray-600 hover:bg-gray-100' }}"
                    >

                        <div class="flex items-center gap-3">

                            @if (!empty($menu['icon']))
                                <x-icon
                                    name="{{ $menu['icon'] }}"
                                    class="w-5 h-5 flex-shrink-0
                                    {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}"
                                />
                            @endif

                            <span x-show="sidebarOpen" class="whitespace-nowrap">
                                {{ $menu['name'] }}
                            </span>
                        </div>

                        <svg x-show="sidebarOpen"
                            :class="open ? 'rotate-90' : ''"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="currentColor">
                            <path d="M6 6L14 10L6 14V6Z" />
                        </svg>

                    </button>

                    {{-- CHILDREN --}}
                    <div x-show="open && sidebarOpen" x-collapse class="ml-8 mt-1 space-y-1">

                        @foreach ($children as $child)

                            @php
                                $childActive = request()->is(ltrim($child['url'], '/') . '*');
                            @endphp

                            <a href="{{ url($child['url']) }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200
                               {{ $childActive
                                    ? 'bg-indigo-100 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-100' }}">

                                <svg class="w-3.5 h-3.5 opacity-70" fill="currentColor">
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
    <div class="border-t border-gray-200 p-4">
        <div class="flex items-center gap-3">

            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>

            <div x-show="sidebarOpen" class="whitespace-nowrap overflow-hidden">
                <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">View Profile</p>
            </div>

        </div>
    </div>

</aside>