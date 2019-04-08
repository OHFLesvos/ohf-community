@extends('layouts.app')

@section('title', __('people::people.edit_helper'))

@section('content')

    {!! Form::model($helper, ['route' => ['people.helpers.update', $helper], 'method' => 'put', 'files' => true]) !!}

        @include('helpers::form')
		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}    

@endsection
