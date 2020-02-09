@extends('layouts.app')

@section('title', __('people.withdrawals'))

@section('content')

    @php
        $lang_arr = lang_arr([
            'app.filter',
            'app.not_found',
            'app.loading',
            'app.registered',
            'app.date',
            'people.recipient',
            'app.amount',
            'people.no_transactions_so_far',
            'app.no_records_matching_your_request'
        ]);
    @endphp

    <div id="bank-app">
        <bank-transactions-page
            api-url="{{ route('api.bank.withdrawal.transactions') }}"
            :lang='@json($lang_arr)'
        ></bank-transactions-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endsection
