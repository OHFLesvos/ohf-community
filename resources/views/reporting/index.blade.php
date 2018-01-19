@extends('layouts.app')

@section('title', 'Reporting')

@section('content')

    <p>Available reports:</p>
    <div class="list-group">
        @foreach(Config::get('reporting.reports') as $report)
            @allowed($report['gate'])
                <a href="{{ route($report['route']) }}" class="list-group-item list-group-item-action">@icon({{ $report['icon'] }}) {{ $report['name'] }}</a>
            @endallowed
        @endforeach
    </div>

@endsection
