@extends('layouts.app')

@section('title', __('people.withdrawals'))

@section('content')
    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.registered')</th>
                        <th>@lang('app.date')</th>
                        <th>@lang('people.recipient')</th>
                        <th>@lang('app.amount')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td title="{{ $transaction->created_at }}">
                            {{ $transaction->created_at->diffForHumans() }}
                            @isset($transaction->user))
                                <small class="text-muted">by {{ $transaction->user->name }}</small>
                            @endisset
                        </td>
                        <td>{{ $transaction->date }}</td>
                        <td>
                            @if($transaction->person != null)
                                <a href="{{ route('people.show', $transaction->person) }}">{{ $transaction->person->family_name }} {{ $transaction->person->name }}</a>
                                @if($transaction->person->gender == 'f')@icon(female) 
                                @elseif($transaction->person->gender == 'm')@icon(male) 
                                @endif
                                @if(isset($transaction->person->date_of_birth))
                                    {{ $transaction->person->date_of_birth }} (@lang('people.age_n', [ 'age' => $transaction->person->age]))
                                @endif
                                @if(isset($transaction->person->nationality))
                                    {{ $transaction->person->nationality }}
                                @endif
                            @else
                                <em>@lang('people.person_deleted')</em>
                            @endif
                        </td>
                        <td>{{ $transaction->amount }} {{ $transaction->couponType->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $transactions->links() }}
        </div>
    @else
        @component('components.alert.info')
            @lang('people.no_transactions_so_far')
        @endcomponent
    @endif
@endsection
