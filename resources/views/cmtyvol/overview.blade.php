@extends('layouts.app')

@section('title', __('Community Volunteers'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-overview-page>
            @lang('Loading...')
        </community-volunteers-overview-page>
    </div>
@endsection
