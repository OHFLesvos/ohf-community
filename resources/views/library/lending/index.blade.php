@extends('library.layouts.library')

@section('title', __('library.library'))

@section('wrapped-content')
    <div id="library-app">
        <lending-page>
            @lang('app.loading')
        </lending-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
