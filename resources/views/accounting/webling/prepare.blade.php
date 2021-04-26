@extends('layouts.app')

@section('title', __('Book to Webling'))

@section('content')
    <p>@lang('The following transactions from <strong>:from</strong> to <strong>:to</strong> can be booked in the period <strong>:period</strong>:', [ 'from' => $from->toDateString(), 'to' => $to->toDateString(), 'period' => $period->title ])</p>
    @unless($transactions->isEmpty())
        {!! Form::open(['route' => ['accounting.webling.store', $wallet ]]) !!}
            {{ Form::hidden('period', $period->id) }}
            {{ Form::hidden('from', $from->toDateString()) }}
            {{ Form::hidden('to', $to->toDateString()) }}
            <div class="table-responsive">
                <table class="table table-hover bg-white" id="bookings_table">
                    <thead>
                        <tr>
                            <th class="fit">@lang('Date')</th>
                            <th class="fit text-right">@lang('Credit')</th>
                            <th class="fit text-right">@lang('Debit')</th>
                            <th>@lang('Posting text')</th>
                            <th>@lang('Debit side')</th>
                            <th>@lang(' Credit side')</th>
                            <th class="fit">@lang('Receipt No.')</th>
                            <th class="fit">@lang('Controlled')</th>
                            <th class="fit">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            @php
                                $posting_text = $transaction->category . ' - ' . (isset($transaction->project) ? $transaction->project .' - ' : '') . $transaction->description;
                            @endphp
                            <tr data-id="{{ $transaction->id }}">
                                <td class="fit">
                                    <a href="{{ route('accounting.transactions.show', $transaction->id) }}" target="_blank" title="@lang('Open in new window/tab')">{{ $transaction->date }}</a>
                                </td>
                                <td class="text-success text-right fit">
                                    @if($transaction->type == 'income') {{ number_format($transaction->amount, 2) }}@endif
                                </td>
                                <td class="text-danger text-right fit">
                                    @if($transaction->type == 'spending') {{ number_format($transaction->amount, 2) }}@endif
                                </td>
                                <td>
                                    {{ Form::bsText('posting_text['.$transaction->id.']', $posting_text, [ 'placeholder' => __('Posting text') ], '') }}

                                </td>
                                <td style="max-width: 8em">
                                    @if($transaction->type == 'income')
                                        {{ Form::bsSelect('debit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => __('Money to') ], '') }}
                                    @elseif($transaction->type == 'spending')
                                        {{ Form::bsSelect('debit_side['.$transaction->id.']', $expenseSelect, null, [ 'placeholder' => __('Paid for') ], '') }}
                                    @endif
                                </td>
                                <td style="max-width: 8em">
                                    @if($transaction->type == 'income')
                                        {{ Form::bsSelect('credit_side['.$transaction->id.']', $incomeSelect, null, [ 'placeholder' => __('Received for') ], '') }}
                                    @elseif($transaction->type == 'spending')
                                        {{ Form::bsSelect('credit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => __('Paid from') ], '') }}
                                    @endif
                                </td>
                                <td class="fit">{{ $transaction->receipt_no }}</td>
                                <td class="fit text-center">
                                    <x-icon-status :check="$transaction->controlled_at" colors/>
                                </td>
                                <td class="fit">
                                    {{ Form::bsRadioList('action['.$transaction->id.']', $actions, $defaultAction, '') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>
                <x-form.bs-submit-button :label="__('Submit')"/>
            </p>
        {!! Form::close() !!}
    @else
        <x-alert type="info">
            @lang('No transactions found.')
        </x-alert>
    @endunless
@endsection

@push('footer')
    <script>
        function updateStatus(row) {
            var id = row.data('id');

            var message = row.find('input[name="posting_text['+id+']"]').val();
            var debit_side = row.find('select[name="debit_side['+id+']"]').val();
            var credit_side = row.find('select[name="credit_side['+id+']"]').val();
            var action = row.find('input[name="action['+id+']"]:checked').val();

            row.removeClass('table-success');
            row.removeClass('table-warning');
            row.removeClass('table-info');

            if (action == 'book') {
                if (message != '' && debit_side != '' && credit_side != '') {
                    row.addClass('table-success');
                } else {
                    row.addClass('table-warning');
                }
            } else {
                if (message != '' && debit_side != '' && credit_side != '') {
                    row.addClass('table-secondary');
                }
            }
        }

        $('#bookings_table input, #bookings_table select').on('change propertychange keyup', function () {
            var row = $(this).parents('tr');
            updateStatus(row);
        });

        $('#bookings_table tbody tr').each(function () {
            var row = $(this);
            updateStatus(row);
        });
    </script>
@endpush
