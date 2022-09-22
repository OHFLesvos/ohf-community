@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <x-oauth-buttons/>

        <div class="form-group">
            {{ Form::email('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'placeholder' => __('Email address') ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required', 'placeholder' => __('Password') ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        {{ Form::hidden('remember', 1) }}

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Login') }}
            </button>
        </div>

        <div class="d-sm-flex justify-content-between">
            <a href="{{ route('register') }}" class="d-block">{{ __('No account? Sign up') }}</a>
            <a href="{{ route('password.request') }}" class="d-block">{{ __('Forgot password?') }}</a>
        </div>

    {{ Form::close() }}

@endsection
