@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.monthly_summary'))

@section('content')
    <div id="people-app">
        <monthly-summary-report-page>
            @lang('app.loading')
        </monthly-summary-report-page>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endpush
