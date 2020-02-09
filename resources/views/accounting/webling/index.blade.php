@extends('layouts.app')

@section('title', __('accounting.book_to_webling'))

@section('content')
    @unless($periods->isEmpty())
        <p>@lang('accounting.please_choose_month_with_unbooked_transactions_in_open_booking_period')</p>
        @foreach($periods as $period_id => $period)
            <h2>{{ $period->title }}</h2>
            @unless($period->months->isEmpty())
                <div class="list-group mb-4 mt-3">
                    @foreach($period->months as $month)
                        <a href="{{ route('accounting.webling.prepare', [ 'period' => $period_id, 'from' => $month->date->toDateString(), 'to' => (clone $month->date->endOfMonth())->toDateString() ]) }}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col">{{ $month->date->formatLocalized('%B %Y') }}</div>
                                <div class="col-auto"><small>{{ $month->transactions }} @lang('accounting.transactions')</small></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                @component('components.alert.info')
                    @lang('accounting.no_months_with_unbooked_transactions_found')
                @endcomponent
            @endunless            
        @endforeach
    @else
        @component('components.alert.info')
            @lang('accounting.no_open_periods_found')
        @endcomponent
    @endunless
@endsection
