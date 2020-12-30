@extends('layouts.app', ['wide_layout' => false])

@section('title', __('wiki.edit_article'))

@section('content')
    {!! Form::model($article, ['route' => ['kb.articles.update', $article], 'method' => 'put']) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('wiki.edit_article')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-sm-8">{{ Form::bsText('title', null, [ 'placeholder' => __('app.title') ], '') }}</div>
                    <div class="col-sm-4">{{ Form::bsText('slug', null, [ 'placeholder' => __('app.slug') ], '') }}</div>
                </div>
                {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content') ], '') }}
                {{ Form::bsTags('tags', $article->tagsSorted->pluck('name'), [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
                {{ Form::bsCheckbox('public', 1, null, __('app.allow_public_access')) }}
                {{ Form::bsCheckbox('featured', 1, null, __('wiki.featured_article')) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.update')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('head')
    <script src="{{ mix('js/editor.js') }}" defer></script>
@endpush

