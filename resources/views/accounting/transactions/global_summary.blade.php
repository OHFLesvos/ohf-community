@extends('layouts.app')

@section('title', __('accounting.accounting'))

@section('content')

    <div class="row">
        <div class="col-md">
            <h2 class="mb-4">@lang('accounting.summary') {{ $heading }} - @lang('accounting.all_wallets')
            </h2>
        </div>
        <div class="col-sm-auto">
            @if(sizeof($months) > 0)
                {{ Form::bsSelect('monthrange', $months, $currentRange, [ 'id' => 'monthrange', 'placeholder' => '- ' . __('app.by_month') . ' -' ], '') }}
            @endif
        </div>
        <div class="col-sm-auto">
            @if(sizeof($years) > 0)
                {{ Form::bsSelect('yearrange', $years, $currentRange, [ 'id' => 'yearrange', 'placeholder' => '- ' . __('app.by_year') . ' -' ], '') }}
            @endif
        </div>
    </div>

    <div class="row">

        {{-- Summary by wallet --}}
        <div class="col-md-12 col-xl-6">
            <div class="card mb-4 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="card-header">
                        <th></th>
                        <th class="text-right">@lang('accounting.income')</th>
                        <th class="text-right">@lang('accounting.spending')</th>
                        <th class="text-right">@lang('accounting.difference')</th>
                        <th class="text-right">@lang('accounting.wallet')</th>
                    </thead>
                    <tbody>
                        @foreach($wallets as $w)
                            <tr>
                                <td>
                                    @can('viewAny', App\Models\Accounting\Wallet::class)
                                        <a href="{{ route('accounting.wallets.doChange', $w['wallet']) }}">
                                    @endcan
                                        {{ $w['wallet']->name }}
                                    @can('viewAny', App\Models\Accounting\MoneyTransaction::class)
                                        </a>
                                    @endcan
                                </td>
                                <td class="text-right">{{ number_format($w['income'], 2) }}</td>
                                <td class="text-right">{{ number_format($w['spending'], 2) }}</td>
                                <td class="text-right {{ $w['income'] > $w['spending'] ? 'text-success' : 'text-danger' }}">{{ number_format($w['income'] - $w['spending'], 2) }}</td>
                                <td class="text-right {{ $w['amount'] > 0 ? 'text-success' : 'text-danger' }}">{{ number_format($w['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><b>@lang('accounting.sum_of_all_wallets')</b></td>
                            <td class="text-right"><b>{{ number_format($income, 2) }}</b></td>
                            <td class="text-right"><b>{{ number_format($spending, 2) }}</b></td>
                            <td class="text-right {{ $income > $spending ? 'text-success' : 'text-danger' }}"><b>{{ number_format($income - $spending, 2) }}</b></td>
                            <td class="text-right {{ $wallet_amount > 0 ? 'text-success' : 'text-danger' }}"><b>{{ number_format($wallet_amount, 2) }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Revenue by categories --}}
        <div class="col-md col-xl-6">
            <div class="card mb-4">
                <div class="card-header">@lang('app.categories')</div>
                <table class="table table-strsiped mb-0">
                    <tbody>
                        @if(count($revenueByCategory) > 0)
                            @foreach($revenueByCategory as $v)
                                <tr>
                                    <td>{{ $v['name'] }}</td>
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
            <div class="col-md col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">@lang('app.secondary_categories')</div>
                    <table class="table mb-0">
                        <tbody>
                            @if(count($revenueBySecondaryCategory) > 0)
                                @foreach($revenueBySecondaryCategory as $v)
                                    <tr>
                                        <td>
                                            @isset($v['name'])
                                                {{ $v['name'] }}
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
        <div class="col-md col-xl-6">
            <div class="card mb-4">
                <div class="card-header">@lang('app.projects')</div>
                <table class="table mb-0">
                    <tbody>
                        @if(count($revenueByProject) > 0)
                            @foreach($revenueByProject as $v)
                                <tr>
                                    <td>
                                        @isset($v['name'])
                                            {{ $v['name'] }}
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
        document.location = '{{ route('accounting.transactions.globalSummary') }}?month=' + month + '&year=' + year;
    });
    $('#yearrange').on('change', function () {
        var val = $(this).val();
        document.location = '{{ route('accounting.transactions.globalSummary') }}?year=' + val;
    });
});
@endsection
