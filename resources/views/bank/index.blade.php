@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    <div class="row">
        @allowed('do-bank-withdrawals')
            <div class="col-sm text-center mb-4">
                <a href="{{ route('bank.withdrawal') }}" class="big-icon">@icon(id-card)</a><br>
                <strong>Withdrawal</strong><br>
                Hand out drachmas, coupons, ...
            </div>
        @endallowed
        @allowed('do-bank-deposits')
            <div class="col-sm text-center mb-4">
                <a href="{{ route('bank.deposit') }}" class="big-icon">@icon(money-bill-alt)</a><br>
                <strong>Deposit</strong><br>
                Register drachmas returned from projects
            </div>
        @endallowed
        @allowed('do-bank-withdrawals')
            <div class="col-sm text-center mb-4">
                <a href="{{ route('bank.prepareCodeCard') }}" class="big-icon">@icon(qrcode)</a><br>
                <strong>Create code card</strong><br>
                Create a printable sheet with code cards
            </div>
        @endallowed
    </div>

@endsection
