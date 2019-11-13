@extends('layouts.app')

@section('title', __('helpers::responsibilities.edit_responsibility'))

@section('content')

    {!! Form::model($responsibility, ['route' => ['people.helpers.responsibilities.update', $responsibility], 'method' => 'put']) !!}

        {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
        {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
        <p>{{ Form::bsCheckbox('available', 1, null, __('app.available')) }}</p>
        <p>{{ Form::bsSubmitButton(__('app.update')) }}</p>

    {!! Form::close() !!}

@endsection
