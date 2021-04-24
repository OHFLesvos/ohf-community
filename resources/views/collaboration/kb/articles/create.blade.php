@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.create_article'))

@section('content')
    {!! Form::open(['route' => ['kb.articles.store']]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('New article')</div>
            <div class="card-body">
                {{ Form::bsText('title', $title, [ 'autofocus', 'placeholder' => __('app.title') ], '') }}
                {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('app.content') ], '') }}
                {{ Form::bsTags('tags', null, [ 'placeholder' => __('app.tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
                {{ Form::bsCheckbox('public', 1, null, __('app.allow_public_access')) }}
                {{ Form::bsCheckbox('featured', 1, null, __('app.featured_article')) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.create')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('head')
    <script src="{{ mix('js/editor.js') }}" defer></script>
@endpush
