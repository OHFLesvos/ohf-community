@extends('layouts.app')

@section('title', 'Bank Charts')

@section('content')

    <div id="app" class="mb-3">
        <h2 class="display-4">Withdrawals</h2>
        <bar-chart
            title="Drachma transactions per day"
            ylabel="Transactions"
            url="{{ route('bank.numTransactions') }}" 
            :height=300
            :legend=false
            class="mb-4">
        </bar-chart>
        <bar-chart
            title="Drachmas handed out per day"
            ylabel="Drachma"
            url="{{ route('bank.sumTransactions') }}"
            :height=300
            :legend=false
            class="mb-2">
        </bar-chart>

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
                    <tr>
                        <td>Drachma</td>
                        <td class="text-right">{{ $avg_sum }}</td>
                        <td class="text-right">{{ $highest_sum->sum }} <small class="text-muted">{{ $highest_sum->date }}</small></td>
                        <td class="text-right">{{ $last_month_sum }}</td>
                        <td class="text-right">{{ $this_month_sum }}</td>
                        <td class="text-right">{{ $last_week_sum }}</td>
                        <td class="text-right">{{ $this_week_sum }}</td>
                        <td class="text-right">{{ $today_sum }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class="display-4">Deposits</h2>

        <line-chart 
            title="Drachma deposited per day" 
            ylabel="Drachma" 
            url="{{ route('bank.depositStats') }}" 
            :height=300>
        </line-chart>

        {{-- List of projects, with cumulated deposits --}}
        @if( ! $projects->isEmpty() )
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover my-5">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th class="text-right">Average</th>
                            <th class="text-right">Highest</th>
                            <th class="text-right">Last month</th>
                            <th class="text-right">This month</th>
                            <th class="text-right">Last week</th>
                            <th class="text-right">This week</th>
                            <th class="text-right">Today</th>
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
                <bar-chart title="Drachma deposited per day from {{ $project->name }}" 
                    ylabel="Drachma"
                    url="{{ route('bank.projectDepositStats', $project) }}"
                    :legend=false
                    :height=300>
                </bar-chart>
            @endforeach

        @endif
        
    </div>

@endsection
