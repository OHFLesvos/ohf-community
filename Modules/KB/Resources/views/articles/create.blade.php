@extends('layouts.app')

@section('title', __('kb::wiki.create_article'))

@section('content')

    {!! Form::open(['route' => ['kb.articles.store']]) !!}

        {{ Form::bsText('title', $title, [ 'autofocus', 'placeholder' => __('app.title') ], '') }}
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content') ], '') }}
        {{ Form::bsTags('tags', null, [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
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
