@extends('widgets.base', [
    'icon' => 'money-bill-alt',
    'title' => __('Accounting'),
    'href' => route('accounting.index'),
])

@section('widget-content')
    <div class="list-group list-group-flush border-top">
        @forelse($wallets as $wallet)
            <a href="{{ $wallet['url'] }}" class="list-group-item list-group-item-action">
                {{ $wallet['name'] }}
                <span class="float-right">{{ $wallet['amount_formatted'] }}</span>
            </a>
        @empty
            <a class="list-group-item">
                {{ __('No wallets found.') }}
            </a>
        @endforelse
    </div>
@endsection
