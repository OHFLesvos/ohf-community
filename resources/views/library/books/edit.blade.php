@extends('layouts.app')

@section('title', __('library.edit_book'))

@section('content')
    <div id="library-app">
        <book-edit-page
            book-id="{{ $book->id }}"
        >
            @lang('app.loading')
        </book-edit-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection

