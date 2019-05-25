@extends('accounting::layouts.accounting')

@section('title', __('accounting::accounting.accounting'))

@section('wrapped-content')

    <div class="row">
        <div class="col-sm">
            <h2 class="mb-4">{{ $monthDate->formatLocalized('%B %Y') }}</h2>
        </div>
        <div class="col-sm-auto">
            @if(sizeof($months) > 0)
                {{ Form::bsSelect('timerange', $months, $monthDate->format('Y-m'), [ 'id' => 'timerange' ], '') }}
            @endif
        </div>
    </div>
    
    <div class="row">
        @foreach([ 'income' => $incomeByCategory, 'spending' => $spendingByCategory ] as $key => $categories)
            <div class="col-sm">
                <div class="card mb-4">
                    <div class="card-header">@lang('accounting::accounting.' . $key)</div>
                    <table class="table table-strsiped mb-0">
                        <tbody>
                            @if(count($categories) > 0)
                                @foreach($categories as $v)
                                    <tr>
                                        <td>
                                            @can('list', Modules\Accounting\Entities\MoneyTransaction::class)
                                                <a href="{{ route('accounting.transactions.index') }}?filter[type]={{ $key }}&filter[category]={{ $v->category }}&filter[date_start]={{ $monthDate->startOfMonth()->toDateString() }}&filter[date_end]={{ $monthDate->endOfMonth()->toDateString() }}">
                                            @endcan
                                                {{ $v->category }}
                                            @can('list', Modules\Accounting\Entities\MoneyTransaction::class)
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="text-right">{{ number_format($v->sum, 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td><em>@lang('app.no_data_available_in_the_selected_time_range')</em></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
        <div class="col-sm">
            <div class="card mb-4">
                <div class="card-header">@lang('app.total')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        <tr>
                            <td>@lang('accounting::accounting.income')</td>
                            <td class="text-right"><u>{{ number_format($incomeByCategory->sum('sum'), 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting::accounting.spending')</td>
                            <td class="text-right"><u>{{ number_format($spendingByCategory->sum('sum'), 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting::accounting.difference')</td>
                            <td class="text-right"><u>{{ number_format($incomeByCategory->sum('sum') - $spendingByCategory->sum('sum'), 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting::accounting.wallet')</td>
                            <td class="text-right"><u>{{ number_format($wallet, 2) }}</u></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	
@endsection

@section('script')
$(function(){
    $('#timerange').on('change', function(){
        var val = $(this).val().split('-');
        document.location = '{{ route('accounting.transactions.summary') }}?year=' + val[0] + '&month=' + val[1];
    });
});
@endsection