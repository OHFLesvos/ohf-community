@extends('layouts.app')

@section('title', __('Book to Webling'))

@section('content')
    @unless($periods->isEmpty())
        <p>{{ __('Please choose a month with unbooked transactions in an open booking period:') }}</p>
        @foreach($periods as $period_id => $period)
            <h2>{{ $period->title }}</h2>
            @unless($period->months->isEmpty())
                <div class="list-group shadow-sm mb-4 mt-3">
                    @foreach($period->months as $month)
                        <a href="{{ route('accounting.webling.prepare', [ $wallet, 'period' => $period_id, 'from' => $month->date->toDateString(), 'to' => (clone $month->date->endOfMonth())->toDateString() ]) }}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col">{{ $month->date->formatLocalized('%B %Y') }}</div>
                                <div class="col-auto"><small>{{ $month->transactions }} {{ __('Transactions') }}</small></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-alert type="info">
                    {{ __('No months with unbooked transactions found.') }}
                </x-alert>
            @endunless
        @endforeach
    @else
        <x-alert type="info">
            {{ __('No matching open booking periods found.') }}
        </x-alert>
    @endunless
@endsection
