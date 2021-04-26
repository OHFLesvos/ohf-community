@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Edit article'))

@section('content')
    {!! Form::model($article, ['route' => ['kb.articles.update', $article], 'method' => 'put']) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('Edit article')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-sm-8">{{ Form::bsText('title', null, [ 'placeholder' => __('Title') ], '') }}</div>
                    <div class="col-sm-4">{{ Form::bsText('slug', null, [ 'placeholder' => __('Slug') ], '') }}</div>
                </div>
                {{ Form::bsTextarea('content', null, [ 'id' => 'editor', 'placeholder' => __('Content') ], '') }}
                {{ Form::bsTags('tags', $article->tagsSorted->pluck('name'), [ 'placeholder' => __('Tags'), 'data-suggestions' => json_encode($tag_suggestions) ], '') }}
                {{ Form::bsCheckbox('public', 1, null, __('Allow public access')) }}
                {{ Form::bsCheckbox('featured', 1, null, __('Featured article')) }}
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('Update')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('head')
    <script src="{{ mix('js/editor.js') }}" defer></script>
@endpush

