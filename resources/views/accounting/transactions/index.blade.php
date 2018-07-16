@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    <p class="text-right">
        @if(count($filter) > 0)
            <a href="{{ route('accounting.transactions.index') }}?reset_filter=1" class="btn btn-sm btn-primary">@icon(eraser) @lang('app.reset_filter')</a>
            <button type="button" class="btn btn-sm btn-secondary" id="filter_results">@icon(search) @lang('app.edit_filter')</button>
        @else
            <button type="button" class="btn btn-sm btn-secondary" id="filter_results">@icon(search) @lang('app.filter_results')</button>
        @endif
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
                        <th class="d-none d-sm-table-cell">@lang('app.description')</th>
                        <th class="d-none d-sm-table-cell @isset($filter['beneficiary']) text-info @endisset">@lang('accounting.beneficiary')</th>
                        <th class="fit @isset($filter['receipt_no']) text-info @endisset"><span class="d-none d-sm-inline">@lang('accounting.receipt') </span>#</th>
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
                            <td class="@isset($transaction->receipt_picture) text-success @endisset">{{ $transaction->receipt_no }}</td>
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

@section('content-footer')
    {!! Form::open(['route' => ['accounting.transactions.index' ], 'method' => 'get']) !!}
    <div class="modal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="javascript:;" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">@lang('app.filter')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
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
                                {{ Form::bsText('filter[project]', $filter['project'] ?? null, [ ], __('app.project')) }}
                            </div>
                            <div class="col-sm">
                                {{ Form::bsText('filter[beneficiary]', $filter['beneficiary'] ?? null, [ ], __('accounting.beneficiary')) }}
                            </div>
                        </div>
                        {{ Form::bsCheckbox('filter[today]', 1, $filter['today'] ?? false, __('accounting.registered_today')) }}
                        <hr>
                        <div class="form-row">
                            <div class="col-sm-auto">
                                {{ Form::bsSelect('sortColumn', $sortColumns, $sortColumn, [], __('app.order_by')) }}
                            </div>
                            <div class="col-sm-auto mb-3">
                                {{ Form::bsRadioList('sortOrder', [ 'asc' => __('app.ascending'), 'desc' => __('app.descending') ], $sortOrder, __('app.order')) }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(count($filter) > 0)
                            <a href="{{ route('accounting.transactions.index') }}?reset_filter=1" class="btn btn-secondary" tabindex="-1">@icon(eraser) @lang('app.reset_filter')</a>
                        @endif
                        {{ Form::bsSubmitButton(__('app.update'), 'search') }}
                    </div>
                </form>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection

@section('script')
$(function(){
    $('#filter_results').on('click', function(){
        $('#filterModal').modal('show');
    });
});
@endsection