<header
    class="sticky top-0 z-30 flex h-16 w-full items-center justify-between bg-white px-4 shadow-sm sm:px-6 lg:px-8 transition-all duration-300">

    <div class="flex items-center gap-x-4 lg:gap-x-6">

        <button type="button" @click="sidebarOpen = !sidebarOpen"
            class="-m-2.5 p-2.5 text-gray-700 hover:text-indigo-600 transition-colors">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

        <div class="relative hidden sm:block w-full max-w-md">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text"
                class="block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50 hover:bg-white transition-colors"
                placeholder="Tìm kiếm nhanh (Ctrl + K)...">
        </div>
    </div>

    <div class="flex items-center gap-x-4 lg:gap-x-6">

        <button type="button"
            class="-m-2.5 p-2.5 text-gray-400 hover:text-indigo-600 transition-colors relative group">
            <span class="sr-only">View notifications</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            <span
                class="absolute top-2 right-2.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
        </button>

        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5 focus:outline-none"
                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>

                <div
                    class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs ring-2 ring-white shadow-sm overflow-hidden">
                    @if (isset($user) && $user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ substr($user->name ?? 'A', 0, 1) }}
                    @endif
                </div>

                <span class="hidden lg:flex lg:items-center">
                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900"
                        aria-hidden="true">{{ $user->name ?? 'Admin' }}</span>
                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
            {{-- Phần code hiển thị Menu cũ của bạn sẽ được thay thế --}}
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                style="display: none;">

                {{-- Thông tin User cho Mobile --}}
                <div class="px-3 py-2 border-b border-gray-100 lg:hidden">
                    <p class="text-sm font-medium text-gray-900">{{ $user->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email ?? '' }}</p>
                </div>

                @foreach ($adminMenuItems as $item)
                    <a href="{{ $item->url ?? $item['url'] ?? '#' }}"
                        class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50" role="menuitem">
                        {{ $item->title ?? $item['title'] }}
                    </a>
                @endforeach

                <hr class="my-1 border-gray-100">

                {{-- Nút Đăng xuất giữ nguyên logic form --}}
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-3 py-1 text-sm leading-6 text-red-600 hover:bg-red-50 font-medium">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
