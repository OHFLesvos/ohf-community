@extends('layouts.app')

@section('title', 'Register Person')

@section('buttons')
    <a href="{{ route( $closeRoute ) }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i><span class="d-none d-md-inline">  Cancel</span></a>
@endsection

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}
		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('name') }}
							{{ Form::text('name', null, [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'autofocus' ]) }}
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('family_name') }}
							{{ Form::text('family_name', null, [ 'class' => 'form-control'.($errors->has('family_name') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('family_name'))
                                <span class="invalid-feedback">{{ $errors->first('family_name') }}</span>
                            @endif
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('case_no', 'Case Number') }}
							{{ Form::number('case_no', null, [ 'class' => 'form-control'.($errors->has('case_no') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('case_no'))
                                <span class="invalid-feedback">{{ $errors->first('case_no') }}</span>
                            @endif
						</div>
					</div>
                    <div class="col-md">
                        <div class="form-group">
                            {{ Form::label('medical_no', 'Medical Number') }}
                            {{ Form::text('medical_no', null, [ 'class' => 'form-control'.($errors->has('medical_no') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('medical_no'))
                                <span class="invalid-feedback">{{ $errors->first('medical_no') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            {{ Form::label('registration_no', 'Registration Number') }}
                            {{ Form::text('registration_no', null, [ 'class' => 'form-control'.($errors->has('registration_no') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('registration_no'))
                                <span class="invalid-feedback">{{ $errors->first('registration_no') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            {{ Form::label('section_card_no', 'Section Card Number') }}
                            {{ Form::text('section_card_no', null, [ 'class' => 'form-control'.($errors->has('section_card_no') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('section_card_no'))
                                <span class="invalid-feedback">{{ $errors->first('section_card_no') }}</span>
                            @endif
                        </div>
                    </div>
				</div>
				<div class="form-row">
                    <div class="col-md">
                        <div class="form-group">
                            {{ Form::label('nationality') }}
                            {{ Form::text('nationality', null, [ 'class' => 'form-control'.($errors->has('nationality') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('nationality'))
                                <span class="invalid-feedback">{{ $errors->first('nationality') }}</span>
                            @endif
                        </div>
                    </div>
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('languages') }}
							{{ Form::text('languages', null, [ 'class' => 'form-control'.($errors->has('languages') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('languages'))
                                <span class="invalid-feedback">{{ $errors->first('languages') }}</span>
                            @endif
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('skills') }}
							{{ Form::text('skills', null, [ 'class' => 'form-control'.($errors->has('skills') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('skills'))
                                <span class="invalid-feedback">{{ $errors->first('skills') }}</span>
                            @endif
						</div>
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('remarks') }}
					{{ Form::text('remarks', null, [ 'class' => 'form-control'.($errors->has('remarks') ? ' is-invalid' : '') ]) }}
                    @if ($errors->has('remarks'))
                        <span class="invalid-feedback">{{ $errors->first('remarks') }}</span>
                    @endif
				</div>
			</div>
		</div>
        @if ( $closeRoute == 'bank.index' )
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('value', 'Transaction') }}
                        {{ Form::number('value', $transaction_value, [ 'class' => 'form-control'.($errors->has('value') ? ' is-invalid' : ''), 'style' => 'width:80px', 'min' => 0, 'max' => $transaction_value ]) }}
                        @if ($errors->has('value'))
                            <span class="invalid-feedback">{{ $errors->first('value') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

		<p>
            {{ Form::button('<i class="fa fa-check"></i> Register', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
        </p>

    {!! Form::close() !!}
    
@endsection
