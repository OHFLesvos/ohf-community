@extends('layouts.app')

@section('title', __('responsibilities.create_responsibility'))

@section('content')

    {!! Form::open(['route' => ['people.helpers.responsibilities.store']]) !!}

        {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, true, __('app.available')) }}</p>
        <p>{{ Form::bsSubmitButton(__('app.update')) }}</p>

    {!! Form::close() !!}

@endsection
