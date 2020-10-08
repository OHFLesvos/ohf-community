@extends('layouts.app')

@section('title', __('accounting.suppliers'))

@section('content')
    <div id="accounting-app">
        <accounting-app>
            @lang('app.loading')
        </accounting-app>
    </div>
@endsection

@section('footer')
    @php
        $permissions = [
            'manage-suppliers' => Gate::allows('manage-suppliers'),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ asset('js/accounting.js') }}?v={{ $app_version }}"></script>
@endsection
