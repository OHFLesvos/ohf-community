@extends('layouts.app')

@section('title', 'Reporting')

@section('content')

    <p>Available reports:</p>
    <div class="list-group">
        @allowed('view-people-reports')
            <a href="{{ route('reporting.people') }}" class="list-group-item list-group-item-action">@icon(users) People</a>
        @endallowed
        @allowed('view-bank-reports')
            <a href="{{ route('reporting.bank.withdrawals') }}" class="list-group-item list-group-item-action">@icon(id-card) Bank: Withdrawals</a>
            <a href="{{ route('reporting.bank.deposits') }}" class="list-group-item list-group-item-action">@icon(money) Bank: Deposits</a>
        @endallowed
    </div>

@endsection
