@extends('layouts.app')

@section('title', __('visitors.visitors'))

@section('content')
    <div id="visitors-app">
        <visitors-app>
            @lang('app.loading')
        </visitors-app>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('js/visitors.js') }}?v={{ $app_version }}"></script>
@endpush
