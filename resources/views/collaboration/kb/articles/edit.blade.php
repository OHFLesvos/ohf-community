@extends('layouts.app', ['wide_layout' => false])

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

@push('head')
    <script src="{{ mix('js/editor.js') }}" defer></script>
@endpush

