@extends('layouts.app')

@section('title', __('app.wallets'))

@section('content')
    <div id="accounting-app">
        <accounting-app>
            @lang('app.loading')
        </accounting-app>
    </div>
@endsection

@push('footer')
    @php
        $permissions = [
            'configure-accounting' => Gate::allows('configure-accounting'),
        ];
    @endphp
    <script>
        window.Laravel.permissions = @json($permissions)
    </script>
    <script src="{{ mix('js/accounting.js') }}"></script>
@endpush
