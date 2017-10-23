@extends('layouts.app')

@section('title', 'Register Person')

@section('buttons')
    <a href="{{ route('people.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Cancel</a>
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
							{{ Form::label('case_no') }}
							{{ Form::number('case_no', null, [ 'class' => 'form-control'.($errors->has('case_no') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('case_no'))
                                <span class="invalid-feedback">{{ $errors->first('case_no') }}</span>
                            @endif
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							{{ Form::label('nationality') }}
							{{ Form::text('nationality', null, [ 'class' => 'form-control'.($errors->has('nationality') ? ' is-invalid' : '') ]) }}
                            @if ($errors->has('nationality'))
                                <span class="invalid-feedback">{{ $errors->first('nationality') }}</span>
                            @endif
						</div>
					</div>
				</div>
				<div class="form-row">
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

		{{ Form::button('<i class="fa fa-plus-circle"></i> Register', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
    {!! Form::close() !!}
    
@endsection
