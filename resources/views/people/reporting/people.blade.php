@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.people'))

@section('content')

    @component('components.date-range-selector', [ 'route' => 'reporting.people', 'dateFrom' => $dateFrom, 'dateTo' => $dateTo ])
        <h2 class="display-4 mb-4">{{ $dateFrom->isoFormat('D. MMMM YYYY') }} - {{ $dateTo->isoFormat('D. MMMM YYYY') }}</h2>
    @endcomponent

    <div id="people-app">
        <people-report-page
            from-date="{{ $dateFrom->toDateString() }}"
            to-date="{{ $dateTo->toDateString() }}"
        >
            @lang('app.loading')
        </people-report-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/people.js') }}?v={{ $app_version }}"></script>
@endsection
