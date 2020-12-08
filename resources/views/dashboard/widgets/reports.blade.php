@extends('dashboard.widgets.base')

@section('widget-title', __('app.reports'))

@section('widget-content')
    <div class="card-body">
        @foreach($reports as $report)
            <a href="{{ $report->url }}">{{ $report->name }}</a><br>
        @endforeach
    </div>
@endsection
