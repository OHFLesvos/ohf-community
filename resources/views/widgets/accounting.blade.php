@extends('widgets.base', [
    'icon' => 'money-bill-alt',
    'title' => __('Accounting'),
    'href' => route('accounting.index'),
])

@section('widget-content')
    <div class="list-group list-group-flush border-top">
        @forelse($wallets as $wallet)
            <a href="{{ route('accounting.transactions.index', $wallet) }}" class="list-group-item list-group-item-action">
                {{ $wallet->name }}
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @empty
            <a class="list-group-item">
                {{ __('No wallets found.') }}
            </a>
        @endforelse
    </div>
@endsection
