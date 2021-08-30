@extends('layouts.app')

@section('title', __('Donation Management'))

@section('content')
    <div id="app">
        <x-spinner />
    </div>
@endsection

@push('head')
    @php
        $permissions = [
            'view-fundraising-entities' => Gate::allows('view-fundraising-entities'),
            'manage-fundraising-entities' => Gate::allows('manage-fundraising-entities'),
            'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
