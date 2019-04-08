@extends('layouts.app')

@section('title', __('people::people.edit_person'))

@section('content')

    {!! Form::model($person, ['route' => ['people.update', $person], 'method' => 'put']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required' ], __('people::people.family_name')) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required' ], __('people::people.name')) }}
					</div>
					<div class="col-md-auto">
						{{ Form::genderSelect('gender', null, __('people::people.gender')) }}
					</div>
                    <div class="col-md-auto">
						{{ Form::bsStringDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], __('people::people.date_of_birth')) }}
					</div>
					<div class="col-md-auto">
						<p>@lang('people::people.age')</p>
						<span id="age">?</span>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, [ 'prepend' => '05/' ], __('people::people.police_number')) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('case_no', null, [ 'placeholder' => $person->case_no_hash != null ? '('. __('app.leave_unchanged') .')' : null  ], __('people::people.case_number')) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('registration_no', null, [], __('people::people.registration_number')) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('section_card_no', null, [], __('people::people.section_card_number')) }}
					</div>
				</div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($countries))], __('people::people.nationality')) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('languages', $person->languages != null ? is_array($person->languages) ? implode(', ', $person->languages) : $person->languages : null, [], __('people::people.languages')) }}
					</div>
				</div>
                {{ Form::bsText('remarks', null, [], __('people::people.remarks')) }}
			</div>
		</div>
			
		<p>
			{{ Form::bsSubmitButton(__('app.update')) }}
		</p>

    {!! Form::close() !!}

@endsection
