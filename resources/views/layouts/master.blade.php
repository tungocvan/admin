<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'FlexBiz Admin')</title>

    {{-- Livewire --}}
    @livewireStyles


    {{-- Custom CSS --}}
    @yield('adminlte_css')
    @stack('css')

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="@yield('classes_body', 'layout-fixed sidebar-expand-lg bg-body-tertiary')" @yield('body_data')>
    {{-- Body Content --}}
    @yield('body')

    {{-- Livewire --}}
    @livewireScripts

    {{-- Custom JS --}}
    @yield('adminlte_js')
    @stack('js')
</body>
</html>
