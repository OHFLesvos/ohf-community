@extends('layouts.app')

@section('title', __('kb::wiki.edit_article'))

@section('content')

    {!! Form::model($article, ['route' => ['kb.articles.update', $article], 'method' => 'put']) !!}

        {{ Form::bsText('title', null, [], __('app.title')) }}
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor' ], __('app.content')) }}
        {{ Form::bsText('tags', $article->tags->sortBy('name')->pluck('name')->implode(', '), [], __('app.tags')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection

@section('footer')
    <script src="{{ asset('js/editor.js') }}?v={{ $app_version }}"></script>
@endsection
