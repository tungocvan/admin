@extends('Admin::layouts.master')

@section('title', 'Quản lý Danh mục')

@section('content')
    @livewire('category.categories.category-table')
@endsection
