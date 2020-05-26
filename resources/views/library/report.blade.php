@extends('layouts.app')

@section('title', __('app.report'))

@section('content')
    <div id="library-app">
        <report-page>
            @lang('app.loading')
        </report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
