@extends('layouts.app')

@section('title', __('wiki.edit_article'))

@section('content')

    {!! Form::model($article, ['route' => ['wiki.articles.update', $article], 'method' => 'put']) !!}

        {{ Form::bsText('title', null, [], __('app.title')) }}
        {{ Form::bsTextarea('content', null, [], __('app.content')) }}
        @include('markdown-help')
        {{ Form::bsText('tags', $article->tags->sortBy('name')->pluck('name')->implode(', '), [], __('app.tags')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
