@extends('layouts.app')

@section('title', __('Summary'))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
    $permissions = [
        'view-accounting-summary' => Gate::allows('view-accounting-summary'),
    ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)

    </script>
@endpush
