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
                    @php
                        $elem = \App\CouponHandout::find($transaction->auditable_id);
                        $amount_diff = 0;
                        if (isset($transaction->getModified()['amount']['new'])) {
                            $amount_diff += $transaction->getModified()['amount']['new'] ;
                        }
                        if (isset($transaction->getModified()['amount']['old'])) {
                            $amount_diff -= $transaction->getModified()['amount']['old'];
                        }
                    @endphp
                    <tr>
                        <td title="{{ $transaction->created_at }}">
                            {{ $transaction->created_at->diffForHumans() }}
                            @isset($transaction->user)
                                <small class="text-muted">by {{ $transaction->user->name }}</small>
                            @endisset
                        </td>
                        @isset($elem)
                            <td>{{ $elem->date }}</td>
                            <td>
                                <a href="{{ route('people.show', $elem->person) }}">{{ $elem->person->family_name }} {{ $elem->person->name }}</a>
                                @if($elem->person->gender == 'f')@icon(female) 
                                @elseif($elem->person->gender == 'm')@icon(male) 
                                @endif
                                    @if(isset($elem->person->date_of_birth))
                                        {{ $elem->person->date_of_birth }} (@lang('people.age_n', [ 'age' => $elem->person->age]))
                                    @endif
                                    @if(isset($elem->person->nationality))
                                        {{ $elem->person->nationality }}
                                    @endif
                            </td>
                            <td>
                                {{ $amount_diff }}
                                {{ $elem->couponType->name }}
                            </td>
                        @else
                            <td colspan="3">
                                @lang('app.not_found')
                            </td>
                        @endisset
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
