@extends('layouts.app')

@section('title', __('people.withdrawals'))

@section('content')

    <div id="bank-app">
        <bank-transactions-page
            api-url="{{ route('api.bank.withdrawal.transactions') }}"
        ></bank-transactions-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endsection
