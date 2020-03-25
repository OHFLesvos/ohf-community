@extends('layouts.app')

@section('title', __('people.register_helper'))

@section('content')

    {!! Form::open(['route' => ['people.helpers.store'], 'files' => true]) !!}

        <div class="columns-2 mb-4">
            @include('helpers.form')
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.register')) }}
        </p>

    {!! Form::close() !!}

@endsection
