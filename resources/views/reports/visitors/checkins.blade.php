@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.visitor_checkins'))

@section('content')
    <div id="reports-app">
        <reports-app>
            @lang('app.loading')
        </reports-app>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/reports.js') }}"></script>
@endpush
