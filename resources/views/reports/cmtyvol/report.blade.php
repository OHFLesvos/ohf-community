@extends('layouts.app')

@section('title', __('Report') . ': ' . __('Community Volunteers'))

@section('content')
    <div id="cmtyvol-app">
        <community-volunteers-report-page>
            <x-spinner />
        </community-volunteers-report-page>
    </div>
@endsection
