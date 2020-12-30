@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reports.people'))

@section('content')
    <div id="people-app">
        <people-report-page>
            @lang('app.loading')
        </people-report-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/people.js') }}"></script>
@endpush
