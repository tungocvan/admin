<header
    class="sticky top-0 z-30 flex h-16 items-center justify-between
           bg-white/80 backdrop-blur-xl
           border-b border-gray-200
           px-4 sm:px-6 lg:px-8
           transition-all duration-300">

    {{-- LEFT --}}
    <div class="flex items-center gap-3 flex-1 min-w-0">

        {{-- MOBILE BUTTON --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-xl bg-white shadow-sm border border-gray-200 hover:bg-gray-100 transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- SEARCH WRAPPER --}}
        <div class="flex-1 min-w-0">
            <div class="w-full max-w-md">
                @livewire('admin.partials.header-search')
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-4">

        @livewire('admin.partials.header-notifications')

        <div class="hidden lg:block h-6 w-px bg-gray-200"></div>

        @livewire('admin.partials.header-user')

    </div>
</header>
