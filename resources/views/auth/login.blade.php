@extends('layouts.login')

@section('title', 'Login')

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <div class="form-group">
            {{ Form::label('email', 'E-Mail Address') }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
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
            <small><a href="{{ route('password.request') }}">Forgot your password?</a></small>
        </div>
 
        <br>
        <button type="submit" class="btn btn-primary btn-block">
            Login
        </button>
        <input type="hidden" name="remember" value="1"/>

        <div class="text-center mt-4">
            Are you new here? <a href="{{ route('register') }}">Create an account</a>
        </div>

    {{ Form::close() }}

@endsection
