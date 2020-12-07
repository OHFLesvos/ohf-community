@extends('layouts.app')

@section('title', __('wiki.create_article'))

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
                {{ Form::bsCheckbox('featured', 1, null, __('wiki.featured_article')) }}
            </div>
        </div>
        <p>
            <x-form.bs-submit-button :label="__('app.create')"/>
        </p>

    {!! Form::close() !!}

@endsection

@push('head')
    <link href="{{ mix('css/summernote-bs4.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ mix('js/summernote-bs4.js') }}" defer></script>
    <script src="{{ mix('js/editor.js') }}" defer></script>
@endpush
