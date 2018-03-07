@extends('layouts.app_bottom_nav')

@section('bottom-nav')
    @php
        $currentRouteName = Route::currentRouteName();
    @endphp
    <a href="{{ route('bank.withdrawal') }}" class="d-block text-center p-2 @if($currentRouteName == 'bank.withdrawal' || $currentRouteName == 'bank.withdrawalSearch') text-primary @else text-muted @endif col" style="text-decoration: none" title="Hand out drachmas, coupons, ...">
        @icon(id-card)<br>Withdrawal
    </a>
    <a href="{{ route('bank.deposit') }}" class="d-block text-center p-2  @if($currentRouteName == 'bank.deposit') text-primary @else text-muted @endif col" style="text-decoration: none" title="Register drachmas returned from projects">
        @icon(money)<br>Deposit
    </a>
@endsection