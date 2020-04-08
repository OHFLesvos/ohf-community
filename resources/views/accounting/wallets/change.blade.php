@extends('layouts.app')

@section('title', __('accounting.wallets'))

@section('content')

    <div class="list-group">
        @foreach($wallets as $wallet)
            <a href="{{ route('accounting.wallets.doChange', $wallet) }}" class="list-group-item list-group-item-action">
                @if($wallet->id == $active->id)
                    <span class="text-success">@icon(check)</span>
                @endif
                {{ $wallet->name }}
                @if($wallet->is_default)
                    (@lang('app.default'))
                @endif
                <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
            </a>
        @endforeach
        <a href="{{ route('accounting.wallets.create') }}" class="list-group-item list-group-item-action">
            <span class="text-primary">@icon(plus-circle)</span>
            @lang('accounting.add_new_wallet')
        </a>
    </div>

@endsection
