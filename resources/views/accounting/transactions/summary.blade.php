@extends('layouts.app')

@section('title', __('accounting.accounting'))

@section('content')

    <div class="row">
        <div class="col-sm">
            <h2 class="mb-4">@lang('accounting.summary') {{ $heading }}
                @if($has_multiple_wallets)
                    <small>
                        {{ $wallet->name }}
                    </small>
                @endif
            </h2>
        </div>
        <div class="col-xl-auto col-md">
            <div class="row">
                @if(sizeof($months) > 0)
                    <div class="col-xl col-sm-6">
                        {{ Form::bsSelect('monthrange', $months, $currentRange, [ 'id' => 'monthrange', 'placeholder' => '- ' . __('app.by_month') . ' -' ], '') }}
                    </div>
                @endif
                @if(sizeof($years) > 0)
                    <div class="col-xl col-sm-6">
                        {{ Form::bsSelect('yearrange', $years, $currentRange, [ 'id' => 'yearrange', 'placeholder' => '- ' . __('app.by_year') . ' -' ], '') }}
                    </div>
                @endif
                @if(sizeof($projects) > 0)
                    <div class="col-xl col-sm-6">
                        {{ Form::bsSelect('project', collect($projects)->mapWithKeys(fn ($e) => [ $e => $e ]), $currentProject, [ 'id' => 'project', 'placeholder' => '- ' . __('app.all_projects') . ' -' ], '') }}
                    </div>
                @endif
                @if(sizeof($locations) > 0)
                    <div class="col-xl col-sm-6">
                        {{ Form::bsSelect('location', collect($locations)->mapWithKeys(fn ($e) => [ $e => $e ]), $currentLocation, [ 'id' => 'location', 'placeholder' => '- ' . __('app.all_locations') . ' -' ], '') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Revenue by categories --}}
        <div class="col-sm-6 col-md">
            <div class="card mb-4">
                <div class="card-header">@lang('app.categories')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($revenueByCategory) > 0)
                            @foreach($revenueByCategory as $v)
                                <tr>
                                    <td>
                                        @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                            <a href="{{ route('accounting.transactions.index', $wallet) }}?filter[category]={{ $v['name'] }}&filter[date_start]={{ $filterDateStart }}&filter[date_end]={{ $filterDateEnd }}">
                                        @endcan
                                            {{ $v['name'] }}
                                        @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                            </a>
                                        @endcan
                                    </td>
                                    <td class="text-right {{ $v['amount'] > 0 ? 'text-success' : 'text-danger' }}">{{ number_format($v['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td><em>@lang('app.no_data_available_in_the_selected_time_range')</em></td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($revenueBySecondaryCategory !== null)
            {{-- Revenue by secondary category --}}
            <div class="col-sm-6 col-md">
                <div class="card mb-4">
                    <div class="card-header">@lang('app.secondary_categories')</div>
                    <table class="table mb-0">
                        <tbody>
                            @if(count($revenueBySecondaryCategory) > 0)
                                @foreach($revenueBySecondaryCategory as $v)
                                    <tr>
                                        <td>
                                            @isset($v['name'])
                                                @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                                    <a href="{{ route('accounting.transactions.index', $wallet) }}?filter[secondary_category]={{ $v['name'] }}&filter[date_start]={{ $filterDateStart }}&filter[date_end]={{ $filterDateEnd }}">
                                                @endcan
                                                    {{ $v['name'] }}
                                                @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                                    </a>
                                                @endcan
                                            @else
                                                <em>@lang('app.no_secondary_category')</em>
                                            @endif
                                        </td>
                                        <td class="text-right {{ $v['amount'] > 0 ? 'text-success' : 'text-danger' }}">{{ number_format($v['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td><em>@lang('app.no_data_available_in_the_selected_time_range')</em></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Revenue by project --}}
        <div class="col-sm-6 col-md">
            <div class="card mb-4">
                <div class="card-header">@lang('app.projects')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($revenueByProject) > 0)
                            @foreach($revenueByProject as $v)
                                <tr>
                                    <td>
                                        @isset($v['name'])
                                            @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                                <a href="{{ route('accounting.transactions.index', $wallet) }}?filter[project]={{ $v['name'] }}&filter[date_start]={{ $filterDateStart }}&filter[date_end]={{ $filterDateEnd }}">
                                            @endcan
                                                {{ $v['name'] }}
                                            @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                                </a>
                                            @endcan
                                        @else
                                            <em>@lang('app.no_project')</em>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $v['amount'] > 0 ? 'text-success' : 'text-danger' }}">{{ number_format($v['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td><em>@lang('app.no_data_available_in_the_selected_time_range')</em></td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Wallet --}}
        <div class="col-sm-6 col-md">
            <div class="card mb-4">
                <div class="card-header">@lang('app.total')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        <tr>
                            <td>@lang('accounting.income')</td>
                            <td class="text-right"><u>{{ number_format($income, 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.spending')</td>
                            <td class="text-right"><u>{{ number_format($spending, 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.transaction_fees')</td>
                            <td class="text-right"><u>{{ number_format($fees, 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.difference')</td>
                            <td class="text-right"><u>{{ number_format($income - $spending, 2) }}</u></td>
                        </tr>
                        <tr>
                            <td>@lang('accounting.wallet')</td>
                            <td class="text-right {{ $wallet_amount < 0 ? 'text-danger' : '' }}"><u>{{ number_format($wallet_amount, 2) }}</u></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
$(function () {
    $('#monthrange').on('change', function () {
        var val = $(this).val();
        var month = ''
        var year = ''
        if (val != '') {
            var arr = val.split('-');
            month = parseInt(arr[1]);
            year = arr[0]
        }
        document.location = '{{ route('accounting.transactions.summary', $wallet) }}?month=' + month + '&year=' + year;
    });
    $('#yearrange').on('change', function () {
        var val = $(this).val();
        document.location = '{{ route('accounting.transactions.summary', $wallet) }}?year=' + val;
    });
    $('#project').on('change', function () {
        var val = $(this).val();
        document.location = '{{ route('accounting.transactions.globalSummary') }}?project=' + val;
    });
    $('#location').on('change', function () {
        var val = $(this).val();
        document.location = '{{ route('accounting.transactions.globalSummary') }}?location=' + val;
    });
});
@endsection
