@extends('layouts.app')

@section('title', __('cmtyvol.community_volunteers') . ' ' . __('app.report'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-report-page>
            @lang('app.loading')
        </community-volunteers-report-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/cmtyvol.js') }}?v={{ $app_version }}"></script>
@endsection
