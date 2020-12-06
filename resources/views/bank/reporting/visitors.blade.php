@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-visitors'))

@section('content')
    <div id="bank-app">
        <bank-visitor-report-page>
            @lang('app.loading')
        </bank-visitor-report-page>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endpush
