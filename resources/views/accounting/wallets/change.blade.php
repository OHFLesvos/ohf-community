@extends('layouts.app')

@section('title', __('accounting.change_wallet'))

@section('content')

    <div class="list-group">
        @forelse($wallets as $wallet)
            <a href="{{ route('accounting.wallets.doChange', $wallet) }}" class="list-group-item list-group-item-action">
                @if($wallet->id == optional($active)->id)
                    <span class="text-success">@icon(check)</span>
                @endif
                {{ $wallet->name }}
                @if($wallet->is_default)
                    <b>(@lang('app.default'))</b>
                @endif
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @empty
            <a class="list-group-item">
                @lang('accounting.no_wallets_found')
            </a>
        @endforelse
    </div>

@endsection
