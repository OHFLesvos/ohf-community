@extends('layouts.app')

@section('title', __('accounting::accounting.book_to_webling'))

@section('content')
    @unless($periods->isEmpty())
        {!! Form::open(['route' => ['accounting.webling.store' ]]) !!}
            @foreach($periods as $period_id => $period)
                {{-- <p>@lang('accounting::accounting.transactions_will_be_booked_in_period', [ 'period' => $period->title, 'from' => $period->from->toDateString(), 'to' => $period->to->toDateString() ])</p> --}}
                <h2>{{ $period->title }} <small>{{ $period->from->toDateString() }} - {{ $period->to->toDateString() }}</small></h2>
                @unless($period->transactions->isEmpty())
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
                                    <th class="fit">@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($period->transactions as $transaction)
                                    @php
                                        $posting_text = $transaction->category . ' - ' . (isset($transaction->project) ? $transaction->project .' - ' : '') . $transaction->description;
                                    @endphp
                                    <tr>  {{-- class="table-secondary" --}}
                                        <td class="fit">
                                            <a href="{{ route('accounting.transactions.show', $transaction->id) }}" target="_blank" title="@lang('app.open_in_new_window')">{{ $transaction->date }}</a>
                                        </td>
                                        <td class="text-success text-right fit">
                                            @if($transaction->type == 'income'){{ number_format($transaction->amount, 2) }}@endif
                                        </td>
                                        <td class="text-danger text-right fit">
                                            @if($transaction->type == 'spending'){{ number_format($transaction->amount, 2) }}@endif
                                        </td>
                                        <td>
                                            {{ Form::bsText('posting_text['.$period_id.']['.$transaction->id.']', $posting_text, [ 'placeholder' => __('accounting::accounting.posting_text') ], '') }}
                                            
                                        </td>
                                        <td style="max-width: 8em">
                                            @if($transaction->type == 'income')
                                                {{ Form::bsSelect('debit_side['.$period_id.']['.$transaction->id.']', $period->assetsSelect, null, [ 'placeholder' => __('accounting::accounting.money_to') ], '') }}
                                            @elseif($transaction->type == 'spending')
                                                {{ Form::bsSelect('debit_side['.$period_id.']['.$transaction->id.']', $period->expenseSelect, null, [ 'placeholder' => __('accounting::accounting.paid_for') ], '') }}
                                            @endif
                                        </td>
                                        <td style="max-width: 8em">
                                            @if($transaction->type == 'income')
                                                {{ Form::bsSelect('credit_side['.$period_id.']['.$transaction->id.']', $period->incomeSelect, null, [ 'placeholder' => __('accounting::accounting.received_for') ], '') }}
                                            @elseif($transaction->type == 'spending')
                                                {{ Form::bsSelect('credit_side['.$period_id.']['.$transaction->id.']', $period->assetsSelect, null, [ 'placeholder' => __('accounting::accounting.paid_from') ], '') }}
                                            @endif
                                        </td>
                                        <td class="fit">{{ $transaction->receipt_no }}</td>
                                        <td class="fit">
                                            {{ Form::bsRadioList('action['.$period_id.']['.$transaction->id.']', $actions, $defaultAction, '') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    @component('components.alert.info')
                        @lang('accounting::accounting.no_transactions_found')
                    @endcomponent
                @endunless
            @endforeach
            <p>
                {{ Form::bsSubmitButton(__('app.submit')) }}
            </p>            
        {!! Form::close() !!}
    @else
        @component('components.alert.info')
            @lang('accounting::accounting.no_open_periods_found')
        @endcomponent
    @endunless
@endsection

@section('script')
    {{-- $(function(){
        $('tbody tr').addClass('table-warning');

        $('input[type="radio"]').on('change', function(){
            var val = $(this).val();
            console.log(val);
            if (val == 'ignore') {
                $(this).parents('tr')
                    .removeClass('table-warning')
                    .removeClass('table-success')
                    .addClass('table-secondary');
            } else if (val == 'book') {
                $(this).parents('tr')
                    .removeClass('table-warning')
                    .addClass('table-success')
                    .removeClass('table-secondary');
            }
        });
        $('select').on('change', function(){
            var val = $(this).val();
            console.log(val);
        });
        $('input[type="text"]').on('change keyup', function(){
            var val = $(this).val();
            console.log(val);
        });
    }); --}}
@endsection