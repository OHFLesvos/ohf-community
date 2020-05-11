@extends('layouts.app')

@section('title', __('calendar.calendar'))

@section('content')
    Currently not implemented!
@endsection

@section('footer')
    <script src="{{ asset('js/calendar.js') }}?v={{ $app_version }}"></script>
@endsection
