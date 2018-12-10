@extends('layouts.app')

@section('title', __('library.edit_book'))

@section('content')
    {!! Form::model($book, ['route' => ['library.books.update', $book], 'method' => 'put']) !!}
        {{ Form::bsText('title', null, [ 'required', 'autofocus' ],  __('app.title')) }}
        {{ Form::bsText('author', null, [ ], __('library.author')) }}
        {{ Form::bsText('language', null, [ ], __('app.language')) }}
        {{ Form::bsText('isbn', null, [ ], __('library.isbn')) }}
        {{ Form::bsSubmitButton(__('app.update')) }}
    {!! Form::close() !!}
@endsection
