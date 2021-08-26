@extends('layouts.app')

@section('title', __('Suppliers'))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
    $permissions = [
        'manage-suppliers' => Gate::allows('manage-suppliers'),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
