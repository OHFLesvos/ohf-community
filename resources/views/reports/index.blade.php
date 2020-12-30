@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.reports'))

@section('content')
    <div class="list-group shadow-sm">
        @foreach($reports as $report)
            <a href="{{ $report['url'] }}" class="list-group-item list-group-item-action">
                <x-icon :icon="$report['icon']"/>
               {{ $report['label'] }}
            </a>
        @endforeach
    </div>
@endsection
