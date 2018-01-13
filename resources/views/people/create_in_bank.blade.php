@extends('layouts.app')

@section('title', 'Register Person')

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required', 'autofocus' ], null, 'Greek: επώνυμο') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required' ], null, 'Greek: όνομα') }}
                    </div>
					<div class="col-md-auto">
                        {{ Form::genderSelect('gender') }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], 'Date of Birth', 'Greek: ημερομηνία γέννησης ') }}
                    </div>
					<div class="col-md-auto">
                        <p>Age</p>
                        <span id="age">?</span>
                    </div>
                </div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, ['prepend' => '05/'], 'Police Number', 'Greek: Δ.Κ.Α.') }}
					</div>
					<div class="col-md">
                        {{ Form::bsNumber('case_no', null, [ ], 'Case Number', 'Greek: Aριθ. Υπ.') }}
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
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off'], null, 'Greek: Υπηκοότητα') }}
                    </div>
					<div class="col-md">
                        {{ Form::bsText('remarks') }}
                    </div>
				</div>
			</div>
		</div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-auto">
                        {{ Form::bsNumber('value', $transaction_value, [ 'style' => 'width:80px', 'min' => 0, 'max' => $transaction_value ], 'Drachma') }}
                    </div>
                    <div class="col-md-auto">
                        <p>Boutique coupon</p>
                        {{ Form::bsCheckbox('boutique_coupon', '1', null, 'Give coupon') }}
                    </div>
                    <div class="col-md-auto">
                        <p>Diaper coupon</p>
                        {{ Form::bsCheckbox('diapers_coupon', '1', null, 'Give coupon') }}
                    </div>
                </div>
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
