@extends('layouts.app')

@section('title', __('wiki.edit_article'))

@section('content')

    {!! Form::model($article, ['route' => ['kb.articles.update', $article], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-sm-8">{{ Form::bsText('title', null, [ 'placeholder' => __('app.title') ], '') }}</div>
            <div class="col-sm-4">{{ Form::bsText('slug', null, [ 'placeholder' => __('app.slug') ], '') }}</div>
        </div>
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content') ], '') }}
        <div class="form-row">
            <div class="col">
                {{ Form::bsTags('tags', $article->tagsSorted->pluck('name'), [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
            </div>
            <div class="col-auto pt-2 pb-3">
                {{ Form::bsCheckbox('public', 1, null, __('app.allow_public_access')) }}
            </div>
            <div class="col-auto pt-2 pb-3">
                {{ Form::bsCheckbox('featured', 1, null, __('wiki.featured_article')) }}
            </div>
        </div>
        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
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
