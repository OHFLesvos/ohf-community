@extends('layouts.app')

@section('title', __('fundraising.donation_management'))

@section('content')
    <div id="fundraising-app">
        <fundraising-app></fundraising-app>
    </div>
@endsection

@section('footer')
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
