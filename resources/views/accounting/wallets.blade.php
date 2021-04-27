@extends('layouts.app')

@section('title', __('Wallets'))

@section('content')
    <div id="accounting-app">
        <accounting-app>
            @lang('Loading...')
        </accounting-app>
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
