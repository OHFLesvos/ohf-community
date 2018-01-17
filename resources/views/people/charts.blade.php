@extends('layouts.app')

@section('title', 'People Charts')

@section('content')

    <div id="app">
        <div class="row mb-0 mb-sm-4">
            <div class="col-xl-6">
            
                {{-- Nationalities --}}
                <div class="card mb-2 mb-sm-4">
                    <div class="card-body">
                        <div>
                            <horizontal-bar-chart
                                title="Nationalities"
                                url="{{ route('people.nationalities') }}"
                                :height=90
                                :legend=false
                                class="mb-2">
                            </horizontal-bar-chart>
                        </div>
                        <table class="table table-sm mb-0 mt-4 colorize">
                            @foreach ($nationalities as $k => $v)
                                <tr>
                                    <td class="colorize-background">&nbsp;</td>
                                    <td>{{ $k }}</td>
                                    <td class="text-right">{{ $v }}</td>
                                    <td class="text-right">{{ round($v / array_sum(array_values($nationalities)) * 100) }} %</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                {{-- Gender --}}
                <div class="card mb-2 mb-sm-4">
                    <div class="card-body">
                        <horizontal-bar-chart
                            title="Gender"
                            url="{{ route('people.genderDistribution') }}"
                            :height=90
                            :legend=false
                            class="mb-2">
                        </horizontal-bar-chart>
                        <div class="row colorize">
                            @foreach ($gender as $k => $v)
                                <div class="col">
                                    <span  class="colorize-background d-inline-block" style="width: 1.5em">&nbsp;</span> {{ $k }}: 
                                    {{ $v }} ({{ round($v / array_sum(array_values($gender)) * 100) }} %)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        
                {{-- Demographics --}}
                <div class="card mb-2 mb-sm-0">
                    <div class="card-body">
                        <horizontal-bar-chart
                            title="Demographics"
                            url="{{ route('people.demographics') }}"
                            :height=90
                            :legend=false
                            class="mb-2">
                        </horizontal-bar-chart>
                        <table class="table table-sm mb-0 mt-4 colorize">
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
                </div>

            </div>
            <div class="col-xl-6">

                {{-- Visitors per week --}}
                <div class="card mb-2 mb-sm-4">
                    <div class="card-body">
                        <bar-chart
                            title="Visitors per day"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerDay') }}"
                            :height=270
                            :legend=false
                            class="mb-2">
                        </bar-chart>
                    </div>
                </div>

                {{-- Visitors per week --}}
                <div class="card mb-2 mb-sm-4">
                    <div class="card-body">
                        <bar-chart
                            title="Visitors per week"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerWeek') }}"
                            :height=270
                            :legend=false
                            class="mb-2">
                        </bar-chart>
                    </div>
                </div>

                {{-- Visitors per month --}}
                <div class="card mb-2 mb-sm-0">
                    <div class="card-body">
                        <bar-chart
                            title="Visitors per month"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerMonth') }}"
                            :height=270
                            :legend=false
                            class="mb-2">
                        </bar-chart>
                    </div>
                </div>
            
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body">
                    <bar-chart
                    title="New registrations per day"
                    ylabel="# Registrations"
                    url="{{ route('people.registrationsPerDay') }}"
                    :height=350
                    :legend=false
                    class="mb-2">
                </bar-chart>
            </div>
        </div>

    </div>

@endsection
