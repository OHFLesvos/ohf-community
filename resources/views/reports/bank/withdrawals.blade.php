@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reports.bank-withdrawals'))

@section('content')
    <div id="bank-app">
        <bank-withdrawals-report-page>
            @lang('app.loading')
        </bank-withdrawals-report-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/bank.js') }}"></script>
@endpush
