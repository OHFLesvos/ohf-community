@php
    $links = [
        [
            'url' => route('reporting.index'),
            'title' => __('app.more_reports'),
            'icon' => 'list',
            'authorized' => true,
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('app.reports'))

@section('widget-content')
    <div class="card-body">
        @foreach($reports as $report)
            <a href="{{ $report->url }}">{{ $report->name }}</a><br>
        @endforeach
    </div>
@endsection
