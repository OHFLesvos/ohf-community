@extends('layouts.app')

@section('title', __('accounting.change_wallet'))

@section('content')

    <div class="list-group">
        @foreach($wallets as $wallet)
            <a href="{{ route('accounting.wallets.doChange', $wallet) }}" class="list-group-item list-group-item-action">
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
    </div>

@endsection
