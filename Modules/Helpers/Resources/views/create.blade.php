@extends('layouts.app')

@section('title', __('people.register_helper'))

@section('content')

    {!! Form::open(['route' => ['people.helpers.store'], 'files' => true]) !!}

        @include('helpers::form')
		<p>
			{{ Form::bsSubmitButton(__('app.register')) }}
		</p>

    {!! Form::close() !!}    

@endsection
