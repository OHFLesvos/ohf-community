@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-withdrawals'))

@section('content')

    <div id="app" class="mb-3">

        <!-- <bar-chart
            title="Drachma transactions per day"
            ylabel="Transactions"
            url="{{ route('reporting.bank.numTransactions') }}" 
            :height=300
            :legend=false
            class="mb-4">
        </bar-chart>
        <bar-chart
            title="Drachmas handed out per day"
            ylabel="Drachma"
            url="{{ route('reporting.bank.sumTransactions') }}"
            :height=300
            :legend=false
            class="mb-2">
        </bar-chart> -->

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover my-5">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-right">Daily average</th>
                        <th class="text-right">Highest</th>
                        <th class="text-right">Last month</th>
                        <th class="text-right">This month</th>
                        <th class="text-right">Last week</th>
                        <th class="text-right">This week</th>
                        <th class="text-right">Today</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $k => $v)
                        <tr>
                            <td>{{ $k }}</td>
                            <td class="text-right">{{ $v['avg_sum'] }}</td>
                            {{-- <td class="text-right">@isset($v->highest_sum){{ $v->highest_sum->sum }} <small class="text-muted">{{ $v->highest_sum->date }}</small>@endisset </td>
                            <td class="text-right">{{ $v->last_month_sum }}</td>
                            <td class="text-right">{{ $v->this_month_sum }}</td>
                            <td class="text-right">{{ $v->last_week_sum }}</td>
                            <td class="text-right">{{ $v->this_week_sum }}</td>
                            <td class="text-right">{{ $v->today_sum }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>

@endsection
