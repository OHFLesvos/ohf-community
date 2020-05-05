@extends('layouts.app')

@section('title', __('helpers.helpers') . ' ' . __('app.report'))

@section('content')

    <div id="app">
        <bar-chart
            title="@lang('people.age_distribution')"
            x-label="@lang('people.age')"
            y-label="@lang('app.quantity')"
            url="{{ route('api.people.helpers.ages') }}"
            :height="350"
            class="mb-2">
        </bar-chart>
        <div class="row mb-4">
            <div class="col-sm">
                <doughnut-chart
                    title="@lang('people.gender')"
                    url="{{ route('api.people.helpers.genders') }}"
                    :height="300"
                    class="mb-2">
                </doughnut-chart>
            </div>
            <div class="col-sm">
                <doughnut-chart
                    title="@lang('people.nationalities')"
                    url="{{ route('api.people.helpers.nationalities') }}"
                    show-legend
                    :height="300"
                    class="mb-2">
                </doughnut-chart>
            </div>
        </div>
    </div>


@endsection
