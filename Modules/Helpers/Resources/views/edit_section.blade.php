@extends('layouts.app')

@section('title', __('people.edit_helper') . ' : ' . $section_label)

@section('content')

    {!! Form::model($helper, ['route' => ['people.helpers.update', $helper, 'section' => $section], 'method' => 'put', 'files' => true]) !!}

        <div class="columns-2 mb-4">
            @include('helpers::form')
        </div>

		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}    

@endsection
