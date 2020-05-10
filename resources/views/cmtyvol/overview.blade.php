@extends('layouts.app')

@section('title', __('cmtyvol.community_volunteers'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-overview-page>
            @lang('app.loading')
        </community-volunteers-overview-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/cmtyvol.js') }}?v={{ $app_version }}"></script>
@endsection
