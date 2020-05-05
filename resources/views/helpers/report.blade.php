@extends('layouts.app')

@section('title', __('helpers.helpers') . ' ' . __('app.report'))

@section('content')
    <div id="helper-app">
        <helpers-report-page>
            @lang('app.loading')
        </helpers-report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/helpers.js') }}?v={{ $app_version }}"></script>
@endsection
