@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.bank-visitors'))

@section('content')

    @component('components.alert.info')
        Visitor data is based on check-ins at the Bank.
    @endcomponent

    <div id="app">
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

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

                {{-- Average visitors per day of week --}}
                <bar-chart
                    title="Average visitors per day of week"
                    ylabel="Avg. # Visitors"
                    url="{{ route('reporting.bank.avgVisitorsPerDayOfWeek') }}"
                    :height=270
                    :legend=false>
                </bar-chart>

            </div>
            <div class="col-xl-6">

                {{-- Visitors per week --}}
                <bar-chart
                    title="Visitors per day"
                    ylabel="# Visitors"
                    url="{{ route('reporting.bank.visitorsPerDay') }}"
                    :height=270
                    :legend=false>
                </bar-chart>

                {{-- Visitors per week --}}
                <bar-chart
                    title="Visitors per week"
                    ylabel="# Visitors"
                    url="{{ route('reporting.bank.visitorsPerWeek') }}"
                    :height=270
                    :legend=false>
                </bar-chart>

                {{-- Visitors per month --}}
                <bar-chart
                    title="Visitors per month"
                    ylabel="# Visitors"
                    url="{{ route('reporting.bank.visitorsPerMonth') }}"
                    :height=270
                    :legend=false>
                </bar-chart>

                {{-- Visitors per year --}}
                <bar-chart
                    title="Visitors per year"
                    ylabel="# Visitors"
                    url="{{ route('reporting.bank.visitorsPerYear') }}"
                    :height=270
                    :legend=false>
                </bar-chart>

            </div>
        </div>

    </div>

@endsection
