@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6">
			
				<div class="card">
					<div class="card-header bg-primary text-light">Reset Password</div>
					<div class="card-body">
					
						@if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif

						<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
							{{ csrf_field() }}

							<div class="form-group">
								{{ Form::label('email', 'E-Mail Address') }}
								{{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
								@if ($errors->has('email'))
									<span class="invalid-feedback">{{ $errors->first('email') }}</span>
								@endif
							</div>

							<div>
								<button type="submit" class="btn btn-primary">
									Send Password Reset Link
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
