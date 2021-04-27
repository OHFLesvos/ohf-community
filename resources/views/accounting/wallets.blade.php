@extends('layouts.app')

@section('title', __('Wallets'))

@section('content')
    <div id="accounting-app">
        @lang('Loading...')
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
