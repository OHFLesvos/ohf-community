@extends('layouts.app')

@section('title', __('helpers.helpers') . ' ' . __('app.report'))

@section('content')

    <div id="app">
        <bar-chart
            title="Age distribution"
            ylabel="# Persons"
            url="{{ route('people.helpers.report.ages') }}"
            :height=350
            :legend=false
            class="mb-2">
        </bar-chart>
        <div class="row mb-4">
            <div class="col-sm">
                <pie-chart
                    title="@lang('people.gender')"
                    url="{{ route('people.helpers.report.genders') }}"
                    :height=300
                    :legend=false
                    class="mb-2">
                </pie-chart>
            </div>
            <div class="col-sm">
                <pie-chart
                    title="@lang('people.nationalities')"
                    url="{{ route('people.helpers.report.nationalities') }}"
                    :height=300
                    :legend=false
                    class="mb-2">
                </pie-chart>
            </div>
        </div>
    </div>


@endsection
