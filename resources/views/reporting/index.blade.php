@extends('layouts.app')

@section('title', __('app.reporting'))

@section('content')

    <p>@lang('app.available_reports'):</p>
    <div class="list-group">
        @foreach(Config::get('reporting.reports') as $key => $report)
            @allowed($report['gate'])
                <a href="{{ route($report['route']) }}" class="list-group-item list-group-item-action">@icon({{ $report['icon'] }}) @lang('reporting.' . $key)</a>
            @endallowed
        @endforeach
    </div>

@endsection
