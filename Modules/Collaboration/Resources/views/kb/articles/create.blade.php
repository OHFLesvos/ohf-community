@extends('layouts.app')

@section('title', __('collaboration::wiki.create_article'))

@section('content')

    {!! Form::open(['route' => ['kb.articles.store']]) !!}

        {{ Form::bsText('title', $title, [ 'autofocus', 'placeholder' => __('app.title') ], '') }}
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content') ], '') }}
        <div class="form-row">
            <div class="col">
                {{ Form::bsTags('tags', null, [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
            </div>
            <div class="col-auto pt-2 pb-3">
                {{ Form::bsCheckbox('public', 1, null, __('app.allow_public_access')) }}
            </div>
            <div class="col-auto pt-2 pb-3">
                {{ Form::bsCheckbox('featured', 1, null, __('collaboration::wiki.featured_article')) }}
            </div>
        </div>
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
