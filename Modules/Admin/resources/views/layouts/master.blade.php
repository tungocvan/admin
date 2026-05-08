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


<body class="h-full bg-gray-50" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <livewire:admin.partials.sidebar />

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <livewire:admin.partials.header />
            <main class="flex-1 overflow-auto p-6">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </div>
    <x-toast />
    @yield('js')
    @stack('scripts')
    @livewireScripts

</body>

</html>
