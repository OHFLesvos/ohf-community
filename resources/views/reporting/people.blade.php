@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.people'))

@section('content')

    <div id="app">
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">
            
                {{-- People --}}
                <div class="card mb-4">
                    <div class="card-header">People</div>
                    <div class="card-body">

                        {{-- Important figues --}}
                        @foreach($people as $row)
                            <div class="row mb-4 align-items-center">
                                @foreach($row as $k => $v)
                                    <div class="col">
                                        <div class="row align-items-center">
                                            <div class="col text-secondary">{{ $k }}:</div>
                                            <div class="col display-4">{{ $v }}</div>
                                        </div>
                                    </div>
                                    <div class="w-100 d-block d-sm-none"></div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Nationalities --}}
                        @if(count($nationalities) > 0)
                            <horizontal-bar-chart
                                title="Nationalities"
                                url="{{ route('reporting.people.nationalities') }}"
                                :height=70
                                :legend=false>
                            </horizontal-bar-chart>
                            <table class="table table-sm mt-2 mb-5 colorize">
                                @foreach ($nationalities as $k => $v)
                                    <tr>
                                        <td class="colorize-background">&nbsp;</td>
                                        <td>{{ $k }}</td>
                                        <td class="text-right">{{ $v }}</td>
                                        <td class="text-right">{{ round($v / array_sum(array_values($nationalities)) * 100) }} %</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        <div class="row">

                            {{-- Gender --}}
                            @if(count($gender) > 0)
                                <div class="col-md-6">
                                    <pie-chart
                                        title="Gender"
                                        url="{{ route('reporting.people.genderDistribution') }}"
                                        :height=300
                                        :legend=false
                                        class="mb-2">
                                    </pie-chart>
                                    <div class="row colorize mb-5">
                                        @foreach ($gender as $k => $v)
                                            <div class="col">
                                                <span  class="colorize-background d-inline-block" style="width: 1.5em">&nbsp;</span> {{ $k }}: 
                                                {{ $v }} ({{ round($v / array_sum(array_values($gender)) * 100) }} %)
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
        
                            {{-- Demographics --}}
                            @if(array_sum(array_values($demographics)) > 0)
                                <div class="col-md-6">
                                    <pie-chart
                                        title="Demographics"
                                        url="{{ route('reporting.people.demographics') }}"
                                        :height=300
                                        :legend=false
                                        class="mb-2">
                                    </pie-chart>
                                    <table class="table table-sm mt-2 mb-5 colorize">
                                        @foreach ($demographics as $k => $v)
                                            <tr>
                                                <td class="colorize-background">&nbsp;</td>
                                                <td>{{ $k }}</td>
                                                <td class="text-right">{{ $v }}</td>
                                                <td class="text-right">{{ round($v / array_sum(array_values($demographics)) * 100) }} %</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Number Types --}}
                        @if(array_sum(array_values($numberTypes)) > 0)
                            <horizontal-bar-chart
                                title="Registered card / person number types"
                                url="{{ route('reporting.people.numberTypes') }}"
                                :height=70
                                :legend=false
                                class="mb-2">
                            </horizontal-bar-chart>
                            <table class="table table-sm mt-2 colorize">
                                @foreach ($numberTypes as $k => $v)
                                    <tr>
                                        <td class="colorize-background">&nbsp;</td>
                                        <td>{{ $k }}</td>
                                        <td class="text-right">{{ $v }}</td>
                                        <td class="text-right">{{ round($v / array_sum(array_values($numberTypes)) * 100) }} %</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if(count($top_names) > 0)
                            <h5 class="text-center"><small>Popular names</small></h5>
                            <table class="table table-sm mt-2">
                                {{-- <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-right">Occurrences</th>
                                    </tr>
                                </thead> --}}
                                <tbody>
                                    @foreach ($top_names as $v)
                                        <tr>
                                            <td>{{ $v->name }}</td>
                                            <td class="text-right">{{ $v->count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        {{-- Registrations per day --}}
                        <bar-chart
                            title="New registrations per day"
                            ylabel="# Registrations"
                            url="{{ route('reporting.people.registrationsPerDay') }}"
                            :height=350
                            :legend=false
                            class="mb-2">
                        </bar-chart>

                    </div>
                </div>

            </div>
            <div class="col-xl-6">

                <div class="card mb-4">
                    <div class="card-header">Visitors <small class="text-muted">based on check-ins at the Bank</small></div>
                    <div class="card-body">

                        {{-- Important figues --}}
                        @foreach($visitors as $row)
                            <div class="row mb-4 align-items-center">
                                @foreach($row as $k => $v)
                                    <div class="col">
                                        <div class="row align-items-center">
                                            <div class="col text-secondary">{{ $k }}:</div>
                                            <div class="col display-4">{{ $v }}</div>
                                        </div>
                                    </div>
                                    <div class="w-100 d-block d-sm-none"></div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Visitors per week --}}
                        <bar-chart
                            title="Visitors per day"
                            ylabel="# Visitors"
                            url="{{ route('reporting.people.visitorsPerDay') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                        {{-- Visitors per week --}}
                        <bar-chart
                            title="Visitors per week"
                            ylabel="# Visitors"
                            url="{{ route('reporting.people.visitorsPerWeek') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>
        
                        {{-- Visitors per month --}}
                        <bar-chart
                            title="Visitors per month"
                            ylabel="# Visitors"
                            url="{{ route('reporting.people.visitorsPerMonth') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                        {{-- Visitors per year --}}
                        <bar-chart
                            title="Visitors per year"
                            ylabel="# Visitors"
                            url="{{ route('reporting.people.visitorsPerYear') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                        {{-- Average visitors per day of week --}}
                        <bar-chart
                            title="Average visitors per day of week"
                            ylabel="Avg. # Visitors"
                            url="{{ route('reporting.people.avgVisitorsPerDayOfWeek') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                    </div>
                </div>
            
            </div>
        </div>

    </div>

@endsection
