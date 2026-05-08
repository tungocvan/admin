@extends('Admin::layouts.master')

@section('title', 'Chat')

@section('content')
    <div class="space-y-4">
        <h1 class="text-2xl font-semibold text-slate-900">Chat module</h1>
        <p class="text-sm text-slate-500">Loai module: support.</p>

        @include('Chat::components.placeholder')
    </div>
@endsection