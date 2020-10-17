@php
    $links = [
        [
            'url' => route('accounting.index'),
            'title' => __('app.overview'),
            'icon' => 'list',
            'authorized' => Auth::user()->can('view', App\Models\Accounting\Wallet::class),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('accounting.accounting'))

@section('widget-content')
    <div class="list-group list-group-flush">
        @forelse($wallets as $wallet)
            <a href="{{ route('accounting.transactions.index', $wallet) }}" class="list-group-item list-group-item-action">
                {{ $wallet->name }}
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @empty
            <a class="list-group-item">
                @lang('accounting.no_wallets_found')
            </a>
        @endforelse
    </div>
@endsection
