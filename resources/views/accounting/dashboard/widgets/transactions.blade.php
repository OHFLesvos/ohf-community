@php
    $links = [
        [
            'url' => route('accounting.transactions.create'),
            'title' => __('app.register'),
            'icon' => 'plus-circle',
            'authorized' => Auth::user()->can('create', App\Models\Accounting\MoneyTransaction::class),
        ],
        ! $has_multiple_wallets ? [
            'url' => route('accounting.transactions.index'),
            'title' => __('app.overview'),
            'icon' => 'list',
            'authorized' =>  Auth::user()->can('viewAny', App\Models\Accounting\MoneyTransaction::class),
        ] : null,
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('accounting.accounting'))

@section('widget-content')
    <div class="list-group list-group-flush">
        @foreach($wallets as $wallet)
            <a href="{{ route('accounting.transactions.index', ['wallet_id' => $wallet->id]) }}" class="list-group-item list-group-item-action">
                @if($has_multiple_wallets){{ $wallet->name }}@else @lang('accounting.wallet') @endif
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @endforeach
    </div>
@endsection
