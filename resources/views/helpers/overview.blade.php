@extends('layouts.app')

@section('title', __('helpers.helpers'))

@section('content')
    <div id="helper-app">
        <helpers-overview-page>
            @lang('app.loading')
        </helpers-overview-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/helpers.js') }}?v={{ $app_version }}"></script>
@endsection
