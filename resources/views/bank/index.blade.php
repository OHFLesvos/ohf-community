@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <div class="row">
        <div class="col-sm text-center mb-4">
            <a href="{{ route('bank.withdrawal') }}" class="big-icon">@icon(id-card)</a><br>
            <strong>Withdrawal</strong><br>
            Hand out drachmas, coupons, ...
        </div>
        <div class="col-sm text-center mb-4">
            <a href="{{ route('bank.deposit') }}" class="big-icon">@icon(money)</a><br>
            <strong>Deposit</strong><br>
            Register drachmas returned from projects
        </div>
        <div class="col-sm text-center mb-4">
            <a href="{{ route('bank.charts') }}" class="big-icon">@icon(line-chart)</a><br>
            <strong>Charts</strong><br>
            View bank utilisation over time
        </div>
    </div>

@endsection
