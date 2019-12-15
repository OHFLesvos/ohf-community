@extends('layouts.app')

@section('title', __('people::people.withdrawals'))

@section('content')
    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.registered')</th>
                        <th>@lang('app.date')</th>
                        <th>@lang('people::people.recipient')</th>
                        <th>@lang('app.amount')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    @php
                        $date = null;
                        if (isset($transaction->getModified()['date'])) {
                            $mdate = $transaction->getModified()['date'];
                            if (isset($mdate['new'])) {
                                $date = new Carbon\Carbon($mdate['new']);
                            } else if (isset($mdate['old'])) {
                                $date = new Carbon\Carbon($mdate['old']);
                            }
                        }

                        $person = null;
                        if (isset($transaction->getModified()['person_id']['new'])) {
                            $person = \Modules\People\Entities\Person::find($transaction->getModified()['person_id']['new']);
                        } else if (isset($transaction->getModified()['person_id']['old'])) {
                            $person = \Modules\People\Entities\Person::find($transaction->getModified()['person_id']['old']);
                        }

                        $amount_diff = 0;
                        if (isset($transaction->getModified()['amount']['new'])) {
                            $amount_diff += $transaction->getModified()['amount']['new'] ;
                        }
                        if (isset($transaction->getModified()['amount']['old'])) {
                            $amount_diff -= $transaction->getModified()['amount']['old'];
                        }

                        $coupon = null;
                        if (isset($transaction->getModified()['coupon_type_id']['new'])) {
                            $coupon = \Modules\Bank\Entities\CouponType::find($transaction->getModified()['coupon_type_id']['new']);
                        } else if (isset($transaction->getModified()['coupon_type_id']['old'])) {
                            $coupon = \Modules\Bank\Entities\CouponType::find($transaction->getModified()['coupon_type_id']['old']);
                        }
                    @endphp
                    <tr>
                        <td title="{{ $transaction->created_at }}">
                            {{ $transaction->created_at->diffForHumans() }}
                            @isset($transaction->user)
                                <small class="text-muted">by {{ $transaction->user->name }}</small>
                            @endisset
                        </td>
                        <td>
                            @isset($date)
                                {{ $date->toDateString() }}
                            @else
                                <em>@lang('app.not_found')</em>
                            @endisset
                        </td>
                        <td>
                            @isset($person)
                                @can('view', $person)
                                    <a href="{{ route('bank.people.show', $person) }}">{{ $person->full_name }}</a>
                                @else
                                    {{ $person->full_name }}
                                @endcan
                                @if($person->gender == 'f')@icon(female)
                                @elseif($person->gender == 'm')@icon(male)
                                @endif
                                @if(isset($person->date_of_birth))
                                    {{ $person->date_of_birth }} (@lang('people::people.age_n', [ 'age' => $person->age]))
                                @endif
                                @if(isset($person->nationality))
                                    {{ $person->nationality }}
                                @endif
                            @else
                                <em>@lang('app.not_found')</em>
                            @endisset
                        </td>
                        <td>
                            @isset($coupon)
                                {{ $amount_diff }}
                                {{ $coupon->name }}
                            @else
                                <em>@lang('app.not_found')</em>
                            @endisset
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $transactions->links() }}
        </div>
    @else
        @component('components.alert.info')
            @lang('people::people.no_transactions_so_far')
        @endcomponent
    @endif
@endsection
