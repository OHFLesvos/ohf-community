@extends('layouts.app')

@section('title', __('accounting.change_wallet'))

@section('content')

    <div class="list-group">
        @foreach($wallets as $wallet)
            <a href="{{ route('accounting.wallets.doChange', $wallet) }}" class="list-group-item list-group-item-action @if($wallet->id == $active->id)list-group-item-success @endif">
                @if($wallet->id == $active->id)
                    <span class="text-success">@icon(check)</span>
                @endif
                {{ $wallet->name }}
                @if($wallet->is_default)
                    <b>(@lang('app.default'))</b>
                @endif
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @endforeach
        @can('create', App\Models\Accounting\Wallet::class)
            <a href="{{ route('accounting.wallets.create') }}" class="list-group-item list-group-item-action">
                <span class="text-primary">@icon(plus-circle)</span>
                @lang('accounting.add_new_wallet')
            </a>
        @endcan
    </div>

@endsection
