@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6">
				<div class="card">
					<div class="card-header bg-primary text-light">Register</div>
					<div class="card-body">
						{{ Form::open(['route' => 'register']) }}

							<div class="form-group">
								{{ Form::label('name', 'Name') }}
								{{ Form::text('name', old('name'), [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
								@if ($errors->has('name'))
									<span class="invalid-feedback">{{ $errors->first('name') }}</span>
								@endif
							</div>

							<div class="form-group">
								{{ Form::label('email', 'E-Mail Address') }}
								{{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
								@if ($errors->has('email'))
									<span class="invalid-feedback">{{ $errors->first('email') }}</span>
								@endif
							</div>

							<div class="form-group">
								{{ Form::label('password', 'Password') }}
								{{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
								@if ($errors->has('password'))
									<span class="invalid-feedback">{{ $errors->first('password') }}</span>
								@endif
							</div>

							<div class="form-group">
								{{ Form::label('password_confirmation', 'Confirm Password') }}
								{{ Form::password('password_confirmation', ['class' => 'form-control'.($errors->has('password_confirmation') ? ' is-invalid' : ''), 'required' ]) }}
								@if ($errors->has('password_confirmation'))
									<span class="invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
								@endif
							</div>

							<div>
								<button type="submit" class="btn btn-primary">
									Register
								</button>
								<a class="btn btn-link" href="{{ route('login') }}">
									Cancel
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
