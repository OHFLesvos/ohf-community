@extends('layouts.accounting')

@section('title', __('accounting.accounting'))

@section('wrapped-content')

    <h2 class="mb-4">{{ $month }}</h2>

    <div class="row">
        <div class="col-sm">
            <div class="card mb-4">
                <div class="card-header">@lang('accounting.income')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($incomeByProject) > 0)
                            @foreach($incomeByProject as $v)
                                <tr>
                                    <td>{{ $v->project }}</td>
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
                                    <td>{{ $v->project }}</td>
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
                            <td class="text-right"><u>{{ number_format($incomeByProject->sum('sum') - $spendingByProject->sum('sum'), 2) }}</u></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

	
@endsection
