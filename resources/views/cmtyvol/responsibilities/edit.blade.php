@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.edit_responsibility'))

@section('content')
    {!! Form::model($responsibility, ['route' => ['cmtyvol.responsibilities.update', $responsibility], 'method' => 'put']) !!}
        {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
        {{ Form::bsTextarea('description', null, [], __('app.description')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, null, __('app.available')) }}</p>
        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>
    {!! Form::close() !!}
@endsection
