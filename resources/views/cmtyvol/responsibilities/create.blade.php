@extends('layouts.app')

@section('title', __('responsibilities.create_responsibility'))

@section('content')

    {!! Form::open(['route' => ['cmtyvol.responsibilities.store']]) !!}

        {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
        {{ Form::bsTextarea('description', null, [], __('app.description')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, true, __('app.available')) }}</p>
        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>

    {!! Form::close() !!}

@endsection
