@extends('Admin::layouts.master')

@section('title', 'Quản lý Phân quyền (Roles)')

@section('content')
    <livewire:role.system.role-table />
@endsection