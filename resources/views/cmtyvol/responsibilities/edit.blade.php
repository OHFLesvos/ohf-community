@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Edit responsibility'))

@section('content')
    {!! Form::model($responsibility, ['route' => ['cmtyvol.responsibilities.update', $responsibility], 'method' => 'put']) !!}
        {{ Form::bsText('name', null, [ 'required' ], __('Name')) }}
        {{ Form::bsTextarea('description', null, [], __('Description')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('Capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, null, __('Available')) }}</p>
        <p>
            <x-form.bs-submit-button :label="__('Update')"/>
        </p>
    {!! Form::close() !!}
@endsection
