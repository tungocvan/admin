<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        use Modules\Admin\Models\Setting;
        $favicon = Setting::getValue('site_favicon');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="icon" href="/favicon.ico" />
    @endif
    <title>@yield('title', 'HOMEPAGE')</title>
    {!! Setting::getValue('header_script') !!}
    @yield('css')
    <script>
        window.CHAT_CONFIG_HOST = "{{ env('NODEJS_SERVER_URL') }}";
        window.CHAT_CONFIG_PORT = "{{ env('NODEJS_SERVER_PORT') ?? 6001 }}";
    </script>
    {{-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> --}}
    @vite(['resources/css/tailwind.css', 'resources/js/tailwind.js'])
    @stack('styles')
    @livewireStyles
</head>

<body class="h-full bg-gray-50" x-data="{
    sidebarOpen: window.innerWidth >= 1024,
    isDesktop: window.innerWidth >= 1024
}" x-init="window.addEventListener('resize', () => {
    isDesktop = window.innerWidth >= 1024;
    if (isDesktop) sidebarOpen = true;
});">

    <div class="flex h-screen overflow-hidden">

        {{-- OVERLAY --}}
        <div x-show="sidebarOpen && !isDesktop" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"></div>

        {{-- SIDEBAR --}}
        <div class="fixed inset-y-0 left-0 z-50 bg-white border-r border-gray-200
           transition-all duration-300 ease-in-out"
            :class="[
                sidebarOpen ?
                'translate-x-0 w-64' :
                '-translate-x-full lg:translate-x-0 lg:w-20'
            ]">
            <livewire:admin.partials.sidebar />
        </div>

        {{-- MAIN --}}
        <div class="flex flex-1 flex-col transition-all duration-300"
            :class="{
                'lg:ml-64': sidebarOpen,
                'lg:ml-20': !sidebarOpen
            }">

            {{-- HEADER --}}
            <livewire:admin.partials.header />

            {{-- CONTENT --}}
            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </main>

        </div>

    </div>

    <x-toast />
    @livewireScripts
</body>

</html>
