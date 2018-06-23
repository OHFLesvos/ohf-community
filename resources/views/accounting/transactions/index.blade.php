@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    @if(count($filter) > 0)
        <p>
            <a href="{{ route('accounting.transactions.index') }}" class="btn btn-sm btn-primary">@lang('app.reset_filter')</a>
        </p>
    @endif

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="fit d-table-cell d-sm-none text-right">@lang('app.amount')</th>
                        <th class="fit d-none d-sm-table-cell text-right">@lang('accounting.income')</th>
                        <th class="fit d-none d-sm-table-cell text-right">@lang('accounting.spending')</th>
                        <th>@lang('app.project')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.description')</th>
                        <th class="d-none d-sm-table-cell">@lang('accounting.beneficiary')</th>
                        <th class="fit d-none d-sm-table-cell">@lang('accounting.receipt') #</th>
                        <th class="fit d-none d-md-table-cell">@lang('app.registered')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fit"><a href="{{ route('accounting.transactions.show', $transaction) }}">{{ $transaction->date }}</a></td>
                            <td class="fit d-table-cell d-sm-none text-right @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">{{ number_format($transaction->amount, 2) }}</td>
                            <td class="fit d-none d-sm-table-cell text-right text-success">@if($transaction->type == 'income'){{ number_format($transaction->amount, 2) }}@endif</td>
                            <td class="fit d-none d-sm-table-cell text-right text-danger">@if($transaction->type == 'spending'){{ number_format($transaction->amount, 2) }}@endif</td>
                            <td>{{ $transaction->project }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->description }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->beneficiary }}</td>
                            <td class="d-none d-sm-table-cell @isset($transaction->receipt_picture) text-success @endisset">{{ $transaction->receipt_no }}</td>
                            @php
                                $audit = $transaction->audits()->latest()->first();
                            @endphp
                            <td class="fit d-none d-md-table-cell">{{ $transaction->created_at }} @isset($audit)({{ $audit->getMetadata()['user_name'] }})@endisset</td>
                        </tr>
                    @endforeach
                </tbody>
                @if(count($filter) > 0)
                    <tfoot>
                        <tr class="d-none d-sm-table-row">
                            <td></td>
                            <td class="text-right d-none d-sm-table-cell text-info"><u>{{ number_format($transactions->where('type', 'income')->sum('amount'), 2) }}</u></td>
                            <td class="text-right d-none d-sm-table-cell text-info"><u>{{ number_format($transactions->where('type', 'spending')->sum('amount'), 2) }}</u></td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <div style="overflow-x: auto">
            {{ $transactions->appends($filter)->links() }}
        </div>
    @else
        @component('components.alert.info')
            @lang('accounting.no_transactions_found')
        @endcomponent
	@endif
	
@endsection
