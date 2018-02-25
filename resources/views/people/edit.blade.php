@extends('layouts.app')

@section('title', 'Edit Person')

@section('content')

    {!! Form::model($person, ['route' => ['people.update', $person], 'method' => 'put']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required' ]) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required' ]) }}
					</div>
					<div class="col-md-auto">
						{{ Form::genderSelect('gender') }}
					</div>
                    <div class="col-md-auto">
							{{ Form::bsStringDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], 'Date of Birth') }}
					</div>
					<div class="col-md-auto">
						<p>Age</p>
						<span id="age">?</span>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, [ 'prepend' => '05/' ], 'Police Number') }}
					</div>
					<div class="col-md">
                        {{ Form::bsNumber('case_no', null, [ ], 'Case Number') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('medical_no', null, [], 'Medical Number') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('registration_no', null, [], 'Registration Number') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('section_card_no', null, [], 'Section Card Number') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('temp_no', null, [], 'Temp Number') }}
					</div>
				</div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($countries))]) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('languages') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('skills') }}
					</div>
				</div>
				{{ Form::bsText('remarks') }}
				<p>{{ Form::bsCheckbox('worker', null, null, 'Person is registered worker') }}</p>
			</div>
		</div>
			
		<p>
			{{ Form::bsSubmitButton('Update') }}
		</p>

    {!! Form::close() !!}

@endsection
