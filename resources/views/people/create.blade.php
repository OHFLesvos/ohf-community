@extends('layouts.app')

@section('title', 'Register Person')

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required', 'autofocus' ]) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required' ]) }}
                    </div>
					<div class="col-md-auto">
                        {{ Form::genderSelect('gender') }}
                    </div>
					<div class="col-md-auto">
                        {{ Form::bsDate('date_of_birth', null, [ ], 'Date of Birth') }}
                    </div>
                </div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, ['prepend' => '05/'], 'Police Number') }}
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
                        {{ Form::bsText('temp_no', null, [], 'Temporary Number') }}
                    </div>
				</div>
				<div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off']) }}
                    </div>
					<div class="col-md">
                        {{ Form::bsText('languages') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('skills') }}
					</div>
				</div>
                {{ Form::bsText('remarks') }}
			</div>
		</div>

		<p>
            {{ Form::bsSubmitButton('Register') }}
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
    $(function(){
        $('#nationality').typeahead({
            source: [ @foreach($countries as $country) '{!! $country !!}', @endforeach ]
        });
    });
@endsection
