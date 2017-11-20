@extends('layouts.app')

@section('title', 'Register Person')

@section('content')

	@component('components.alert.info')
		Please ensure to use currect <strong>english language</strong> country names for nationalities, e.g. 'Syria', 'Afghanistan', 'Iraq', 'Iraqi Kurdistan', .... as proposed by the auto-completion feature.
		This helps us to have accurate <a href="{{ route('people.charts') }}">statistics</a>.
	@endcomponent

    {!! Form::open(['route' => 'people.store']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('name', null, [ 'required', 'autofocus' ]) }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('family_name', null, [ 'required' ]) }}
					</div>
				</div>
				<div class="form-row">
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
        @if ( $allow_transaction )
            <div class="card mb-4">
                <div class="card-body">
                    {{ Form::bsNumber('value', $transaction_value, [ 'style' => 'width:80px', 'min' => 0, 'max' => $transaction_value ], 'Transaction') }}
                </div>
            </div>
        @endif

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
