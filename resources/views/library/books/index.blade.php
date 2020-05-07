@extends('library.layouts.library')

@section('title', __('library.library'))

@section('wrapped-content')
    <div id="library-app">
        <books-page>
            @lang('app.loading')
        </books-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
