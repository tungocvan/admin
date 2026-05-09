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
        @livewire('admin.partials.header-search')
    </div>

    <div class="flex items-center gap-x-4 lg:gap-x-6">        
        @livewire('admin.partials.header-notifications')
        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>
        @livewire('admin.partials.header-user')
        
    </div>
</header>
