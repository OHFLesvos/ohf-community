@extends('layouts.app')

@section('title', __('accounting::accounting.book_to_webling'))

@section('content')
    @unless($periods->isEmpty())
        <p>Bitte wähle eine Monat mit unverbuchten Transaktionen in einer offenen Buchungsperiode:</p>
        @foreach($periods as $period_id => $period)
            <h2>{{ $period->title }}</h2>
            <div class="list-group mb-4 mt-3">
                @foreach($period->months as $month)
                    <a href="{{ route('accounting.webling.prepare', [ 'period' => $period_id, 'from' => $month->date->toDateString(), 'to' => (clone $month->date->endOfMonth())->toDateString() ]) }}" class="list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col">{{ $month->date->formatLocalized('%B %Y') }}</div>
                            <div class="col-auto"><small>{{ $month->transactions }} @lang('accounting::accounting.transactions')</small></div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
    @else
        @component('components.alert.info')
            @lang('accounting::accounting.no_open_periods_found')
        @endcomponent
    @endunless
@endsection
