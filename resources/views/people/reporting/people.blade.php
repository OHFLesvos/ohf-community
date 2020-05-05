@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.people'))

@section('content')

    @component('components.date-range-selector', [ 'route' => 'reporting.people', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo ])
        <h2 class="display-4 mb-4">{{ $dateFrom->isoFormat('D. MMMM YYYY') }} - {{ $dateTo->isoFormat('D. MMMM YYYY') }}</h2>
    @endcomponent

    <div id="app">
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

                {{-- Registrations --}}
                <div class="card mb-4">
                    <div class="card-header">@lang('people.registrations')</div>
                    <div class="card-body">

                        {{-- Important figues --}}
                        @foreach($people as $row)
                            <div class="row mb-4 align-items-center">
                                @foreach($row as $k => $v)
                                    <div class="col">
                                        <div class="row align-items-center">
                                            <div class="col text-secondary">{{ $k }}:</div>
                                            <div class="col display-4">{{ number_format($v) }}</div>
                                        </div>
                                    </div>
                                    <div class="w-100 d-block d-sm-none"></div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Registrations per day --}}
                        <bar-chart
                            title="@lang('app.new_registrations_per_day')"
                            x-label="@lang('app.date')"
                            y-label="@lang('app.quantity')"
                            url="{{ route('api.people.reporting.registrationsPerDay', [ 'from' => $dateFrom->toDateString(), 'to' => $dateTo->toDateString() ]) }}"
                            :height="350"
                            class="mb-0">
                        </bar-chart>
                    </div>
                </div>

                {{-- Gender --}}
                <div class="card mb-4">
                    <div class="card-header">@lang('people.gender')</div>
                    <div class="card-body">
                        <doughnut-chart
                            title="@lang('people.gender')"
                            url="{{ route('api.people.reporting.genderDistribution') }}"
                            :height="300"
                            class="mb-2">
                        </doughnut-chart>
                    </div>
                </div>

                {{-- Age distribution --}}
                @if(array_sum(array_values($ageDistribution)) > 0)
                    <div class="card mb-4">
                        <div class="card-header">@lang('people.age_distribution')</div>
                        <div class="card-body">
                            <doughnut-chart
                                title="@lang('people.age_distribution')"
                                url="{{ route('api.people.reporting.ageDistribution') }}"
                                show-legend
                                :height="300"
                                class="mb-2">
                            </doughnut-chart>
                            <table class="table table-sm mb-0 colorize">
                                @foreach ($ageDistribution as $k => $v)
                                    <tr>
                                        <td class="colorize-background" style="width: 2em">&nbsp;</td>
                                        <td>{{ $k }}</td>
                                        <td class="text-right">{{ round($v / array_sum(array_values($ageDistribution)) * 100, 1) }} %</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Cards --}}
                <div class="card mb-4">
                    <div class="card-header">@lang('app.cards')</div>
                    <div class="card-body">
                        @foreach($cards as $row)
                            <div class="row mb-4 align-items-center">
                                @foreach($row as $k => $v)
                                    <div class="col">
                                        <div class="row align-items-center">
                                            <div class="col text-secondary">{{ $k }}:</div>
                                            <div class="col display-4">{{ number_format($v) }}</div>
                                        </div>
                                    </div>
                                    <div class="w-100 d-block d-sm-none"></div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-xl-6">

                {{-- Nationalities --}}
                @if(count($nationalities) > 0)
                    <div class="card mb-4">
                        <div class="card-header">@lang('people.nationalities')</div>
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
        </div>
    </div>

@endsection
