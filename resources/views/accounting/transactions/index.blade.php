@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    <p class="text-right">
        @if(count($filter) > 0)
            <a href="{{ route('accounting.transactions.index') }}?reset_filter=1" class="btn btn-sm btn-primary">@icon(eraser) @lang('app.reset_filter')</a>
        @endif
        <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#filterModal">
            @icon(search) @lang(count($filter) > 0 ? 'app.edit_filter' : 'app.filter_results')
        </button>
    </p>

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit @if(isset($filter['date_start']) || isset($filter['date_end']) || isset($filter['month'])) text-info @endisset">@lang('app.date')</th>
                        <th class="fit d-table-cell d-sm-none text-right">@lang('app.amount')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='income') text-info @endisset">@lang('accounting.income')</th>
                        <th class="fit d-none d-sm-table-cell text-right @if(isset($filter['type']) && $filter['type']=='spending') text-info @endisset">@lang('accounting.spending')</th>
                        <th class="@isset($filter['project']) text-info @endisset">@lang('app.project')</th>
                        <th class="d-none d-sm-table-cell @isset($filter['description']) text-info @endisset">@lang('app.description')</th>
                        <th class="d-none d-sm-table-cell @isset($filter['beneficiary']) text-info @endisset">@lang('accounting.beneficiary')</th>
                        <th class="fit @if(isset($filter['receipt_no']) || isset($filter['no_receipt'])) text-info @endif"><span class="d-none d-sm-inline">@lang('accounting.receipt') </span>#</th>
                        <th class="fit d-none d-md-table-cell @isset($filter['today']) text-info @endisset">@lang('app.registered')</th>
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
                            <td class="@isset($transaction->receipt_picture) text-success @endisset">{{ $transaction->receipt_no }}</td>
                            @php
                                $audit = $transaction->audits()->first();
                            @endphp
                            <td class="fit d-none d-md-table-cell">{{ $transaction->created_at }} @isset($audit)({{ $audit->getMetadata()['user_name'] }})@endisset</td>
                        </tr>
                    @endforeach
                </tbody>
                @if(count($filter) > 0)
                    @php
                        $sum_income = $transactions->where('type', 'income')->sum('amount');
                        $sum_spending = $transactions->where('type', 'spending')->sum('amount');
                    @endphp
                    @if($sum_income > 0 || $sum_spending > 0)
                        <tr>
                            <td>@lang('app.total')</td>
                            <td class="text-right d-none d-sm-table-cell">
                                <u class="text-success">{{ number_format($sum_income, 2) }}</u>
                            </td>
                            <td class="text-right d-none d-sm-table-cell">
                                <u class="text-danger">{{ number_format($sum_spending, 2) }}</u>
                            </td>
                            <td class="text-right d-table-cell d-sm-none">
                                @if($sum_income > 0)<u class="text-success">{{ number_format($sum_income, 2) }}</u><br>@endif
                                @if($sum_spending > 0)<u class="text-danger">{{ number_format($sum_spending, 2) }}</u>@endif
                            </td>
                            <td colspan="5"></td>
                        </tr>
                    @endif
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

@section('content-footer')
    {!! Form::open(['route' => ['accounting.transactions.index' ], 'method' => 'get']) !!}
        @component('components.modal', [ 'id' => 'filterModal' ])
            @slot('title', __('app.filter'))

            <div class="form-row">
                <div class="col-sm mb-3">
                    {{ Form::bsRadioList('filter[type]', [ 'income' => __('accounting.income'), 'spending' => __('accounting.spending'), null => __('app.any'),  ], $filter['type'] ?? null, __('app.type')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsNumber('filter[receipt_no]', $filter['receipt_no'] ?? null, [ 'min' => 1 ], __('accounting.receipt') . ' #') }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsDate('filter[date_start]', $filter['date_start'] ?? null, [], __('app.from')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsDate('filter[date_end]', $filter['date_end'] ?? null, [], __('app.to')) }}
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsText('filter[project]', $filter['project'] ?? null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($projects)) ], __('app.project')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsText('filter[beneficiary]', $filter['beneficiary'] ?? null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($beneficiaries)) ], __('accounting.beneficiary')) }}
                </div>
            </div>
            {{ Form::bsText('filter[description]', $filter['description'] ?? null, [ ], __('app.description')) }}
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsCheckbox('filter[today]', 1, $filter['today'] ?? false, __('accounting.registered_today')) }}
                </div>
                <div class="col-sm">
                    {{ Form::bsCheckbox('filter[no_receipt]', 1, $filter['no_receipt'] ?? false, __('accounting.no_receipt')) }}
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="col-sm-auto">
                    {{ Form::bsSelect('sortColumn', $sortColumns, $sortColumn, [], __('app.order_by')) }}
                </div>
                <div class="col-sm-auto mb-3">
                    {{ Form::bsRadioList('sortOrder', [ 'asc' => __('app.ascending'), 'desc' => __('app.descending') ], $sortOrder, __('app.order')) }}
                </div>
            </div>

            @slot('footer')
                @if(count($filter) > 0)
                    <a href="{{ route('accounting.transactions.index') }}?reset_filter=1" class="btn btn-secondary" tabindex="-1">@icon(eraser) @lang('app.reset_filter')</a>
                @endif
                {{ Form::bsSubmitButton(__('app.update'), 'search') }}
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
