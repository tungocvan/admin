@extends('layouts.master')

@section('title', $title ?? 'FlexBiz Admin')

@section('body')
<div class="app-wrapper">

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Content Wrapper --}}
    <main class="app-main">
        <div class="app-content p-3">
            @yield('content')
        </div>

    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

</div>
@endsection

