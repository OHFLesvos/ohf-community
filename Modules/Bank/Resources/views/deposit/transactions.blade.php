@extends('layouts.app')

@section('title', __('people::people.deposits'))

@section('content')

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.registered')</th>
                        <th>@lang('app.date')</th>
                        <th>@lang('people::people.project')</th>
                        <th>@lang('app.amount')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $transaction)
                    @php
                        $elem = \Modules\Bank\Entities\CouponReturn::find($transaction->auditable_id);
                        $amount_diff = $transaction->getModified()['amount']['new'] ;
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
                            <td>{{ $elem->project->name }}</td>
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
        </div>
        {{ $transactions->links() }}
    @else
        @component('components.alert.info')
            @lang('people::people.no_transactions_so_far')
        @endcomponent
    @endif

@endsection