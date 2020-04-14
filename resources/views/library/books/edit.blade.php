@extends('layouts.app')

@section('title', __('library.edit_book'))

@section('content')
    {!! Form::model($book, ['route' => ['library.books.update', $book], 'method' => 'put']) !!}
        {{ Form::bsText('isbn', null, [ ], __('library.isbn')) }}
        {{ Form::bsText('title', null, [ 'required' ],  __('app.title')) }}
        {{ Form::bsText('author', null, [ ], __('library.author')) }}
        {{ Form::bsSelect('language_code', $languages, null, [ 'placeholder' => __('app.choose_language') ], __('app.language')) }}
        {{ Form::bsSubmitButton(__('app.update')) }}
    {!! Form::close() !!}
@endsection
