@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

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
        <div class="col-sm">
            <div class="card mb-4">
                <div class="card-header">@lang('accounting.income')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($incomeByProject) > 0)
                            @foreach($incomeByProject as $v)
                                <tr>
                                    <td>
                                        <a href="{{ route('accounting.transactions.index') }}?filter[type]=income&filter[project]={{ $v->project }}&year={{ $monthDate->year }}&month={{ $monthDate->month }}">
                                            {{ $v->project }}
                                        </a>
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
        <div class="col-sm">
            <div class="card mb-4">
                <div class="card-header">@lang('accounting.spending')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($spendingByProject) > 0)
                            @foreach($spendingByProject as $v)
                                <tr>
                                    <td>
                                        <a href="{{ route('accounting.transactions.index') }}?filter[type]=spending&filter[project]={{ $v->project }}&year={{ $monthDate->year }}&month={{ $monthDate->month }}">
                                            {{ $v->project }}
                                        </a>
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
        <div class="col-sm">
            <div class="card mb-4">
                <div class="card-header">@lang('app.total')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        <tr>
                            <td>@lang('accounting.income')</td>
                            <td class="text-right"><u>{{ number_format($incomeByProject->sum('sum'), 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.spending')</td>
                            <td class="text-right"><u>{{ number_format($spendingByProject->sum('sum'), 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.wallet')</td>
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