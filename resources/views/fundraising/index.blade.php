@extends('layouts.app')

@section('title', __('Donation Management'))

@section('content')
    <div id="fundraising-app">
        @lang('Loading...')
    </div>
@endsection

@push('head')
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
@endpush
