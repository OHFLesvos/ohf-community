@extends('layouts.app')

@section('title', __('Categories'))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
    $permissions = [
        'configure-accounting' => Gate::allows('configure-accounting'),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
