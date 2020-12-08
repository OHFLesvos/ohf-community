@extends('layouts.app')

@section('title', __('app.report') . ': ' . __('reporting.community_volunteers'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-report-page>
            @lang('app.loading')
        </community-volunteers-report-page>
    </div>
@endsection

@push('footer')
    <script src="{{ mix('js/cmtyvol.js') }}"></script>
@endpush
