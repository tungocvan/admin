@extends('Admin::layouts.master')
@section('title', 'Cập nhật nhân viên')
@section('content')
    <livewire:user.system.staff-form :id="$id" />
@endsection
