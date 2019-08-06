@extends('layouts.app')

@section('title', __('accounting::accounting.accounting'))

@section('content')
    @isset($period)

        <p>@lang('accounting::accounting.transactions_will_be_booked_in_period', [ 'period' => $period->title, 'from' => $period->from->toDateString(), 'to' => $period->to->toDateString() ])</p>

        @if( ! $transactions->isEmpty() )
            {!! Form::open(['route' => ['accounting.transactions.store' ]]) !!}
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="fit">@lang('app.date')</th>
                            <th class="fit text-right">@lang('accounting::accounting.credit')</th>
                            <th class="fit text-right">@lang('accounting::accounting.debit')</th>
                            <th>@lang('accounting::accounting.posting_text')</th>
                            <th>@lang('accounting::accounting.debit_side')</th>
                            <th>@lang('accounting::accounting.credit_side')</th>
                            <th class="fit">@lang('accounting::accounting.receipt_no')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            @php
                                $posting_text = $transaction->category . ' - ' . (isset($transaction->project) ? $transaction->project .' - ' : '') . $transaction->description;
                            @endphp
                            <tr>  {{-- class="table-secondary" --}}
                                <td class="fit">{{ $transaction->date }}</td>
                                <td class="text-success text-right fit">
                                    @if($transaction->type == 'income'){{ number_format($transaction->amount, 2) }}@endif
                                </td>
                                <td class="text-danger text-right fit">
                                    @if($transaction->type == 'spending'){{ number_format($transaction->amount, 2) }}@endif
                                </td>
                                <td>
                                    {{ Form::bsText('posting_text['.$transaction->id.']', $posting_text, [ 'placeholder' => __('accounting::accounting.posting_text') ], '') }}
                                    
                                </td>
                                <td>
                                    @if($transaction->type == 'income')
                                        {{ Form::bsSelect('debit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => 'Geld nach' ], '') }}
                                    @elseif($transaction->type == 'spending')
                                        {{ Form::bsSelect('debit_side['.$transaction->id.']', $expenseSelect, null, [ 'placeholder' => 'Bezahlt für' ], '') }}
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->type == 'income')
                                        {{ Form::bsSelect('credit_side['.$transaction->id.']', $incomeSelect, null, [ 'placeholder' => 'Erhalten für' ], '') }}
                                    @elseif($transaction->type == 'spending')
                                        {{ Form::bsSelect('credit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => 'Bezahlt aus' ], '') }}
                                    @endif
                                </td>
                                <td class="fit">{{ $transaction->receipt_no }}</td>
                                <td>
                                    {{ Form::bsRadioList('action['.$transaction->id.']', $actions, 'book', '') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>
                {{ Form::bsSubmitButton(__('app.submit')) }}
            </p>            
            {!! Form::close() !!}
        @else
            @component('components.alert.info')
                @lang('accounting::accounting.no_transactions_found')
            @endcomponent
        @endif
    @else
        @component('components.alert.info')
            @lang('accounting::accounting.no_open_periods_found')
        @endcomponent
    @endif
@endsection
