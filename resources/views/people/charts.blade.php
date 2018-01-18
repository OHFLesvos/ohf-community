@extends('layouts.app')

@section('title', 'People Charts')

@section('content')

    <div id="app">
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">
            
                {{-- People --}}
                <div class="card mb-4">
                    <div class="card-header">People</div>
                    <div class="card-body">

                        {{-- Nationalities --}}
                        <horizontal-bar-chart
                            title="Nationalities"
                            url="{{ route('people.nationalities') }}"
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

                        {{-- Gender --}}
                        <horizontal-bar-chart
                            title="Gender"
                            url="{{ route('people.genderDistribution') }}"
                            :height=70
                            :legend=false
                            class="mb-2">
                        </horizontal-bar-chart>
                        <div class="row colorize mb-5">
                            @foreach ($gender as $k => $v)
                                <div class="col">
                                    <span  class="colorize-background d-inline-block" style="width: 1.5em">&nbsp;</span> {{ $k }}: 
                                    {{ $v }} ({{ round($v / array_sum(array_values($gender)) * 100) }} %)
                                </div>
                            @endforeach
                        </div>
        
                        {{-- Demographics --}}
                        <horizontal-bar-chart
                            title="Demographics"
                            url="{{ route('people.demographics') }}"
                            :height=70
                            :legend=false
                            class="mb-2">
                        </horizontal-bar-chart>
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

                        {{-- Number Types --}}
                        <horizontal-bar-chart
                            title="Registered card / person number types"
                            url="{{ route('people.numberTypes') }}"
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

                    </div>
                </div>

            </div>
            <div class="col-xl-6">

                <div class="card mb-4">
                    <div class="card-header">Visitors</div>
                    <div class="card-body">

                        {{-- Visitors per week --}}
                        <bar-chart
                            title="Visitors per day"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerDay') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                        {{-- Visitors per week --}}
                        <bar-chart
                            title="Visitors per week"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerWeek') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>
        
                        {{-- Visitors per month --}}
                        <bar-chart
                            title="Visitors per month"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerMonth') }}"
                            :height=270
                            :legend=false>
                        </bar-chart>

                        {{-- Visitors per year --}}
                        <bar-chart
                            title="Visitors per year"
                            ylabel="# Visitors"
                            url="{{ route('people.visitorsPerYear') }}"
                            :height=270
                            :legend=false>
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
