@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Create article'))

@section('content')
    {!! Form::open(['route' => ['kb.articles.store']]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('New article')</div>
            <div class="card-body">
                {{ Form::bsText('title', $title, [ 'autofocus', 'placeholder' => __('Title') ], '') }}
                {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('Content') ], '') }}
                {{ Form::bsTags('tags', null, [ 'placeholder' => __('Tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
                {{ Form::bsCheckbox('public', 1, null, __('Allow public access')) }}
                {{ Form::bsCheckbox('featured', 1, null, __('Featured article')) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('Create')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
