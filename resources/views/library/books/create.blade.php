@extends('layouts.app')

@section('title', __('library.register_book'))

@section('content')
    <div id="library-app">
        <book-register-page>
            @lang('app.loading')
        </book-register-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
