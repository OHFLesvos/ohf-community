@extends('layouts.app')

@section('title', __('wiki::wiki.create_article'))

@section('content')

    {!! Form::open(['route' => ['wiki.articles.store']]) !!}

        {{ Form::bsText('title', null, [ 'autofocus' ], __('app.title')) }}
        {{ Form::bsTextarea('content', null, [], __('app.content')) }}
        @include('markdown-help')
        {{ Form::bsText('tags', null, [], __('app.tags')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection