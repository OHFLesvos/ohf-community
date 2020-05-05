@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-withdrawals'))

@section('content')
    <div id="bank-app">
        <bank-withdrawals-report-page>
            @lang('app.loading')
        </bank-withdrawals-report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
