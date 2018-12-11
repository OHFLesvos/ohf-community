@extends('layouts.app')

@section('title', __('library.register_book'))

@section('content')
    {!! Form::open(['route' => ['library.books.store'], 'method' => 'post']) !!}
        {{ Form::bsText('isbn', null, [ 'autofocus' ], __('library.isbn')) }}
        {{ Form::bsText('title', null, [ 'required' ],  __('app.title')) }}
        {{ Form::bsText('author', null, [ ], __('library.author')) }}
        {{ Form::bsText('language', null, [ ], __('app.language')) }}
        {{ Form::bsSubmitButton(__('app.register')) }}
    {!! Form::close() !!}
@endsection

@section('footer')
    <script src="{{ asset('js/library.js') }}?v={{ $app_version }}"></script>
@endsection
