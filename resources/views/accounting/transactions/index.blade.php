@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    @if(count($filter) > 0)
        <p>
            <a href="{{ route('accounting.transactions.index') }}" class="btn btn-sm btn-primary">@lang('app.reset_filter')</a>
        </p>
    @endif
    <div class="card mb-4">
        <div class="card-body">
            {!! Form::open(['route' => ['accounting.transactions.index' ], 'method' => 'get']) !!}
                <div class="form-row">
                    <div class="col-sm-auto">
                        {{ Form::bsDate('filter[date]', $filter['date'] ?? null, [], __('app.date')) }}
                    </div>
                    <div class="col-sm-auto">
                        {{ Form::bsNumber('filter[receipt_no]', $filter['receipt_no'] ?? null, [ 'min' => 1 ], __('accounting.receipt')) }}
                    </div>
                    <div class="col-sm-auto">
                        {{ Form::bsSelect('sortColumn', $sortColumns, $sortColumn, [], __('app.order_by')) }}
                    </div>
                    <div class="col-sm-auto mb-3">
                        {{ Form::bsRadioInlineList('sortOrder', [ 'asc' => __('app.ascending'), 'desc' => __('app.descending') ], $sortOrder, __('app.order')) }}
                    </div>
                </div>
                {{ Form::bsSubmitButton(__('app.update'), 'search') }}
            {!! Form::close() !!}
        </div>
    </div>

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit @if(isset($filter['date']) || isset($filter['year']) || isset($filter['month'])) text-info @endisset">@lang('app.date')</th>
                        <th class="fit d-table-cell d-sm-none text-right">@lang('app.amount')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='income') text-info @endisset">@lang('accounting.income')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='spending') text-info @endisset">@lang('accounting.spending')</th>
                        <th class="@isset($filter['project']) text-info @endisset">@lang('app.project')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.description')</th>
                        <th class="d-none d-sm-table-cell  @isset($filter['beneficiary']) text-info @endisset">@lang('accounting.beneficiary')</th>
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
