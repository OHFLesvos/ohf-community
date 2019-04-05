@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-deposits'))

@section('content')

    <div id="app" class="mb-3">
 
        <line-chart 
            title="@lang('people::people.coupons_returned_per_day')" 
            ylabel="# @lang('people::people.coupons')"
            url="{{ route('reporting.bank.depositStats') }}" 
            :height=300>
        </line-chart>

        {{-- List of projects, with cumulated deposits --}}
        @if( ! $projects->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover my-5">
                    <thead>
                        <tr>
                            <th>@lang('people::people.project')</th>
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
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td class="text-right">{{ $project->avgNumTransactions() }}</td>
                            <td class="text-right">{{ $project->maxNumTransactions() }}</td>
                            <td class="text-right">{{ $project->monthTransactions(Carbon\Carbon::today()->startOfMonth()->subMonth()) }}</td>
                            <td class="text-right">{{ $project->monthTransactions(Carbon\Carbon::today()) }}</td>
                            <td class="text-right">{{ $project->weekTransactions(Carbon\Carbon::today()->startOfWeek()->subWeek()) }}</td>
                            <td class="text-right">{{ $project->weekTransactions(Carbon\Carbon::today()) }}</td>
                            <td class="text-right">{{ $project->dayTransactions(Carbon\Carbon::today()) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @foreach ($projects as $project)
                <bar-chart title="@lang('people::people.coupons_returned_per_day_from_project', [ 'project' => $project->name ])" 
                    ylabel="# @lang('people::people.coupons')"
                    url="{{ route('reporting.bank.projectDepositStats', $project) }}"
                    :legend=false
                    :height=300>
                </bar-chart>
            @endforeach

        @endif
        
    </div>

@endsection
