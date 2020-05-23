@extends('layouts.app')

@section('title', __('app.report'))

@section('content')
    <div id="fundraising-app">
        <report-page>
            @lang('app.loading')
        </report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/fundraising.js') }}?v={{ $app_version }}"></script>
@endsection
