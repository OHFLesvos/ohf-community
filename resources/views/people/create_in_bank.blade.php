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

		<div class="card mb-4 d-none" id="children-card">
            <div class="card-body">
                @foreach(range(0,4) as $r)
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('child_family_name['.$r.']', null, [ 'placeholder' => 'Child Family Name' ], '', 'Greek: επώνυμο') }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('child_name['.$r.']', null, [ 'placeholder' => 'Child Name'  ], '', 'Greek: όνομα') }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::genderSelect('child_gender['.$r.']', null, '') }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsDate('child_date_of_birth['.$r.']', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], '', 'Greek: ημερομηνία γέννησης ') }}
                    </div>
                    <div class="col-md-auto">
                        <span id="age">?</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

		<p>
            {{ Form::bsSubmitButton('Register') }}
            <button type="button" class="btn btn-secondary" id="add-children">@icon(child) Add children</button>
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
    $(function(){
        $('#nationality').typeahead({
            source: [ @foreach($countries as $country) '{!! $country !!}', @endforeach ]
        });
        $('#add-children').on('click', function(){
            $(this).hide();
            $('#children-card').removeClass('d-none');
        });
    });
@endsection
