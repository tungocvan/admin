@extends('layouts.master')

@section('classes_body', 'login-page bg-body-tertiary')

@section('body')
<div class="login-box">
    <div class="card card-outline card-primary shadow-lg">
        <div class="card-header text-center">
            <h3 class="fw-bold">FlexBiz Admin</h3>
        </div>

        <div class="card-body">
            @yield('content')
        </div>
    </div>
</div>
@endsection
