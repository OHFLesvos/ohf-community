@extends('layouts.app')

@section('title', __('accounting.transactions'))

@section('content')

    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="fit text-right">@lang('accounting.income')</th>
                        <th class="fit text-right">@lang('accounting.spending')</th>
                        <th class="fit d-none d-sm-table-cell">@lang('accounting.receipt') #</th>
                        <th class="d-none d-sm-table-cell">@lang('accounting.beneficiary')</th>
                        <th>@lang('app.project')</th>
                        <th class="d-none d-sm-table-cell">@lang('app.description')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fit">{{ $transaction->date }}</td>
                            <td class="fit text-right text-success">@if($transaction->type == 'income'){{ $transaction->amount }}@endif</td>
                            <td class="fit text-right text-danger">@if($transaction->type == 'spending'){{ $transaction->amount }}@endif</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->receipt_no }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->beneficiary }}</td>
                            <td>{{ $transaction->project }}</td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    @else
        @component('components.alert.info')
            @lang('accounting.no_transactions_found')
        @endcomponent
	@endif
	
@endsection
