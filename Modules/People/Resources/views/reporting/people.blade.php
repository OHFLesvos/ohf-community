@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.people'))

@section('content')

    <div id="app">
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

                {{-- Registrations --}}
                <div class="card mb-4">
                    <div class="card-header">@lang('people::people.registrations')</div>
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

                        {{-- Registrations per day --}}
                        <bar-chart
                            title="New registrations per day"
                            ylabel="# Registrations"
                            url="{{ route('reporting.people.registrationsPerDay') }}"
                            :height=350
                            :legend=false
                            class="mb-0">
                        </bar-chart>

                    </div>
                </div>

                {{-- Gender --}}
                @if(count($gender) > 0)
                    <div class="card mb-4">
                        <div class="card-header">@lang('people::people.gender')</div>
                        <div class="card-body">
                            <pie-chart
                                title="@lang('people::people.gender')"
                                url="{{ route('reporting.people.genderDistribution') }}"
                                :height=300
                                :legend=false
                                class="mb-2">
                            </pie-chart>
                            <table class="table table-sm mb-0 colorize">
                                @foreach ($gender as $k => $v)
                                    <tr>
                                        <td class="colorize-background" style="width: 2em">&nbsp;</td>
                                        <td>{{ $k }}</td>
                                        <td class="text-right">{{ round($v / array_sum(array_values($gender)) * 100) }} %</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Demographics --}}
                @if(array_sum(array_values($demographics)) > 0)
                    <div class="card mb-4">
                        <div class="card-header">@lang('people::people.demographics')</div>
                        <div class="card-body">
                            <pie-chart
                                title="@lang('people::people.demographics')"
                                url="{{ route('reporting.people.demographics') }}"
                                :height=300
                                :legend=false
                                class="mb-2">
                            </pie-chart>
                            <table class="table table-sm mb-0 colorize">
                                @foreach ($demographics as $k => $v)
                                    <tr>
                                        <td class="colorize-background" style="width: 2em">&nbsp;</td>
                                        <td>{{ $k }}</td>
                                        <td class="text-right">{{ round($v / array_sum(array_values($demographics)) * 100) }} %</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Nationalities --}}
                @if(count($nationalities) > 0)
                    <div class="card mb-4">
                        <div class="card-header">@lang('people::people.nationalities')</div>
                        <div class="table-responsive mb-0">
                            <table class="table table-sm my-0 colorize">
                                @foreach ($nationalities as $nationality => $v)
                                    @php
                                        $percent = round($v / array_sum(array_values($nationalities)) * 100, 1);
                                    @endphp
                                    <tr>
                                        <td class="fit">{{ $nationality }}</td>
                                        <td class="align-middle d-none d-sm-table-cell">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td class="fit text-right">{{ $percent }}%</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

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
