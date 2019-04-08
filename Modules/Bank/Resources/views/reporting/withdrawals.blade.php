@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-withdrawals'))

@section('content')

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover mb-4">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-right">@lang('app.daily_average')</th>
                    <th class="text-right">@lang('app.highest')</th>
                    <th class="text-right">@lang('app.last_month')</th>
                    <th class="text-right">@lang('app.this_month')</th>
                    <th class="text-right">@lang('app.last_week')</th>
                    <th class="text-right">@lang('app.this_week')</th>
                    <th class="text-right">@lang('app.today')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $v)
                    <tr>
                        <td>{{ $v['coupon']->name }}</td>
                        <td class="text-right">{{ $v['avg_sum'] }}</td>
                        <td class="text-right">@isset($v['highest_sum']){{ $v['highest_sum']->sum }} <small class="text-muted">{{ $v['highest_sum']->date }}</small>@endisset </td>
                        <td class="text-right">{{ $v['last_month_sum'] }}</td>
                        <td class="text-right">{{ $v['this_month_sum'] }}</td>
                        <td class="text-right">{{ $v['last_week_sum'] }}</td>
                        <td class="text-right">{{ $v['this_week_sum'] }}</td>
                        <td class="text-right">{{ $v['today_sum'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="app" class="mb-3">

        @foreach($coupons as $v)
            <bar-chart
                title="@lang('people::people.num_x_handed_out_per_day', [ 'name' => $v['coupon']->name ])"
                ylabel="# {{ $v['coupon']->name }}"
                url="{{ route('reporting.bank.couponsHandedOutPerDay', $v['coupon']) }}?from={{ $from }}&to={{ $to }}"
                :height=300
                :legend=false
                class="mb-2">
            </bar-chart>
        @endforeach
        
    </div>

@endsection
