@extends('layouts.app')

@section('title', __('accounting.accounting'))

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>@lang('accounting.wallets')</span>
            @can('configure-accounting')
                <a href="{{ route('accounting.wallets') }}">@lang('app.manage')</a>
            @endcan
        </div>
        <div class="list-group list-group-flush">
            @forelse($wallets as $wallet)
                @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                    <a href="{{ route('accounting.wallets.doChange', $wallet) }}" class="list-group-item list-group-item-action">
                        {{ $wallet->name }}
                        <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
                    </a>
                @else
                    <div class="list-group-item">{{ $wallet->name }}</div>
                @endcan
            @empty
                <a class="list-group-item">
                    @lang('accounting.no_wallets_found')
                </a>
            @endforelse
        </div>
    </div>

@endsection
