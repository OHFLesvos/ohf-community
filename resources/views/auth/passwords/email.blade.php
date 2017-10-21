@extends('layouts.login')

@section('content')

	<h1 class="display-4 text-center">Reset password</h1>
	<br>

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

@endsection
