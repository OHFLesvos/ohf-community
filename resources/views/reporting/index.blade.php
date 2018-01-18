@extends('layouts.app')

@section('title', 'Reporting')

@section('content')

    <p>Available reports:</p>
    <div class="list-group">
        <a href="{{ route('reporting.people') }}" class="list-group-item list-group-item-action">@icon(users) People</a>
        @allowed('view-bank-statistics')
            <a href="{{ route('reporting.bank.withdrawals') }}" class="list-group-item list-group-item-action">@icon(users) Bank: Withdrawals</a>
            <a href="{{ route('reporting.bank.deposits') }}" class="list-group-item list-group-item-action">@icon(users) Bank: Deposits</a>
        @endallowed
    </div>

@endsection
