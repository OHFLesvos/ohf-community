@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Accounting'))

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between">
            <span>{{ __('Wallets') }}</span>
            @can('configure-accounting')
                <a href="{{ route('accounting.wallets') }}">{{ __('Manage') }}</a>
            @endcan
        </div>
        <div class="list-group list-group-flush">
            @forelse($wallets as $wallet)
                @can('viewAny', App\Models\Accounting\Transaction::class)
                    <a href="{{ route('accounting.transactions.index', $wallet) }}" class="list-group-item list-group-item-action">
                        {{ $wallet->name }}
                        <span class="float-right">{{ number_format($wallet->amount, 2) }}</span>
                    </a>
                @else
                    <div class="list-group-item">{{ $wallet->name }}</div>
                @endcan
            @empty
                <div class="list-group-item">
                    {{ __('No wallets found.') }}
                </div>
            @endforelse
        </div>
    </div>

    <p>
        @can('viewAny', App\Models\Accounting\Category::class)
            <a href="{{ route('accounting.categories') }}" class="btn btn-secondary">{{ __('Manage categories') }}</a>
        @endcan
        @can('viewAny', App\Models\Accounting\Project::class)
            <a href="{{ route('accounting.projects') }}" class="btn btn-secondary">{{ __('Manage projects') }}</a>
        @endcan
    </p>
@endsection
