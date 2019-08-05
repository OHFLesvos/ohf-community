@extends('layouts.app')

@section('title', __('accounting::accounting.accounting'))

@section('content')

    @foreach($periods as $period)
        <h2>{{ $period->title }} <small>{{ $period->state }}</small></h2>
        <p>Date range: {{ $period->from->toDateString() }} - {{ $period->to->toDateString() }}</p>

        <h3>Einnahmen buchen</h3>
        {{ Form::bsSelect('assetsSelect', $assetsSelect, null, [], 'Geld nach') }}
        {{ Form::bsSelect('incomeSelect', $incomeSelect, null, [], 'Erhalten für') }}

        <h3>Ausgabe buchen</h3>
        {{ Form::bsSelect('expenseSelect', $expenseSelect, null, [], 'Bezahlt für') }}
        {{ Form::bsSelect('assetsSelect', $assetsSelect, null, [], 'Bezahlt aus') }}


        {{-- @foreach($period->accountGroups()->sortBy('type') as $accountGroup)
            <h3>{{ $accountGroup->title }} <small>{{ $accountGroup->type }}</small></h3>
            <ul>
                @foreach($accountGroup->accounts()->sortBy('title') as $account)
                    <li>
                        {{ $account->title }} ({{ $account->amount }})
                    </li>
                @endforeach
            </ul>
        @endforeach --}}
    @endforeach

@endsection
