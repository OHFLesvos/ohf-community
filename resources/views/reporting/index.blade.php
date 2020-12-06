@extends('layouts.app')

@section('title', __('app.reporting'))

@section('content')

    <p>@lang('app.available_reports'):</p>
    <div class="list-group">
        @foreach(config('reporting.reports') as $key => $report)
            @canany($report['gate'])
                <a href="{{ isset($report['route']) ? route($report['route']) : $report['url'] }}" class="list-group-item list-group-item-action">
                    <x-icon :icon="$report['icon']"/>
                    @lang('reporting.' . $key)
                </a>
            @endcanany
        @endforeach
    </div>

@endsection
