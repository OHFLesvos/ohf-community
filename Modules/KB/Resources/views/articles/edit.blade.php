@extends('layouts.app')

@section('title', __('kb::wiki.edit_article'))

@section('content')

    {!! Form::model($article, ['route' => ['kb.articles.update', $article], 'method' => 'put']) !!}

        {{ Form::bsText('title', null, [ 'placeholder' => __('app.title') ], '') }}
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content'), 'data-upload-url' => route('kb.images.store') ], '') }}
        {{ Form::bsTags('tags', $article->tagsSorted->pluck('name'), [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection

@section('head-meta')
    <link href="{{ asset('css/summernote-bs4.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
@endsection

@section('footer')
    <script src="{{ asset('js/summernote-bs4.js') }}?v={{ $app_version }}"></script>
    <script src="{{ asset('js/editor.js') }}?v={{ $app_version }}"></script>
@endsection
