@extends('layouts.page')

@section('title', 'TEMPLATE HTML BOOSTRAP 5')

@section('content')

    @include('Template::template.'.$component)
    {{-- @include('template.time-line') --}}
    {{-- @include('template.ui-elements') --}}
    {{-- @include('template.form') --}}

    {{-- @include('template.tables') --}}
    {{-- @include('template.info-box') --}}
    {{-- @include('template.cards') --}}
    {{-- @include('template.action-toolbar') --}}
    {{-- @include('Template::template.table-order') --}}

@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
@endpush

@push('js')
     <script>
        document.addEventListener("DOMContentLoaded", () => {
            console.log("Lắng nghe sự kiện DOMContentLoaded được gọi trước jquery");
        })
     </script>
@endpush
