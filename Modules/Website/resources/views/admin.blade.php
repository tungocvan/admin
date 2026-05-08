@extends('layouts.page')

@section('title', 'TEMPLATE HTML BOOSTRAP 5')

@section('content')
    <h3>TEMPLATE HTML BOOSTRAP 5</h3>

@endsection

@push('css')
        {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

@push('js')
     <script>
        document.addEventListener("DOMContentLoaded", () => {
            console.log("Lắng nghe sự kiện DOMContentLoaded được gọi trước jquery");
        })
     </script>
@endpush

