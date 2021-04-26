@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Create responsibility'))

@section('content')
    {!! Form::open(['route' => ['cmtyvol.responsibilities.store']]) !!}
        {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('Name')) }}
        {{ Form::bsTextarea('description', null, [], __('Description')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('Capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, true, __('Available')) }}</p>
        <p>
            <x-form.bs-submit-button :label="__('Update')"/>
        </p>
    {!! Form::close() !!}
@endsection
