@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6">
			
				<div class="card">
					<div class="card-header bg-primary text-light">Login</div>
					<div class="card-body">
						{{ Form::open(['route' => 'login']) }}

							<div class="form-row align-items-end">
								<div class="form-group col">
									{{ Form::label('email', 'E-Mail Address') }}
									{{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
									@if ($errors->has('email'))
										<span class="invalid-feedback">{{ $errors->first('email') }}</span>
									@endif
								</div>
								<div class="form-group col-md-auto">
									<a class="btn btn-link" href="{{ route('register') }}">
										Register
									</a>
								</div>
							</div>

							<div class="form-row align-items-end">
								<div class="form-group col">
									{{ Form::label('password', 'Password') }}
									{{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
									@if ($errors->has('password'))
										<span class="invalid-feedback">{{ $errors->first('password') }}</span>
									@endif
								</div>
								<div class="form-group col-md-auto">
									<a class="btn btn-link" href="{{ route('password.request') }}">
										Forgot Your Password?
									</a>
								</div>
							</div>

							<div class="row align-items-center">
								<div class="col-auto">
									<button type="submit" class="btn btn-primary">
										Login
									</button>
								</div>
								<div class="col">
									<label class="form-check-label">
										<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="form-check-input">
										Remember Me
									</label>
								</div>
							</div>
							{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
