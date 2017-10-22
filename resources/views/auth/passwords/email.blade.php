@extends('layouts.login')

@section('content')

    <h1 class="display-4 text-center">Reset password</h1>
    <br>

    @if (session('status'))
        <div class="alert alert-success">
            <i class="fa fa-check"></i> {{ session('status') }}
        </div>
    @endif
    
    <p>Please enter your e-mail address. We will send you a link which allows you to reset your password.</p>

    {{ Form::open(['route' => 'password.email']) }}

        <div class="form-group">
            {{ Form::label('email', 'E-Mail Address') }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            Send Password Reset Link
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">Return to login</a>
        </div>

    {{ Form::close() }}

@endsection
