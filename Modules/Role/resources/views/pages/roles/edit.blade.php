@extends('Admin::layouts.master')

@section('title', 'Chỉnh sửa Vai trò')

@section('content')
    <livewire:role.system.role-form :id="$id" />
@endsection