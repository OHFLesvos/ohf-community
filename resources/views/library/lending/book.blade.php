@extends('layouts.app')

@section('title', __('library.library') . ': ' .__('library.book'))

@section('content')
    <div id="library-app">
        <book-show-page book-id="{{ $book->id }}">
            @lang('app.loading')
        </book-show-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
