@extends('layouts.app')

@section('title', __('people.withdrawals'))

@section('content')
    <div id="bank-app">
        <bank-transactions-page></bank-transactions-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/bank.js') }}" defer></script>
@endpush
