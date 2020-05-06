@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('people.person'))

@section('content')
    <div id="library-app">
        <lending-person-page person-id="{{ $person->getRouteKey() }}">
            @lang('app.loading')
        </lending-person-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
