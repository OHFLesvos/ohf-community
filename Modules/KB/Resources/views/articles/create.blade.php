@extends('layouts.app')

@section('title', __('kb::wiki.create_article'))

@section('content')

    {!! Form::open(['route' => ['kb.articles.store']]) !!}

        {{ Form::bsText('title', $title, [ 'autofocus' ], __('app.title')) }}
        {{ Form::bsTextarea('content', null, [ 'id' => 'editor' ], __('app.content')) }}
        {{ Form::bsText('tags', null, [], __('app.tags')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection

@section('footer')
    <script src="{{ asset('js/editor.js') }}?v={{ $app_version }}"></script>
@endsection
