@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.people'))

@section('content')
    <div id="people-app">
        <people-report-page>
            @lang('app.loading')
        </people-report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection
