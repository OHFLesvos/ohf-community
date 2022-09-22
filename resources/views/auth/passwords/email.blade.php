@extends('layouts.login')

@section('title', __('Reset password'))

@section('content')

    @if (session('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <p>{{ __('Please enter your email address. We will send you a link which allows you to reset your password.') }}</p>

    {{ Form::open(['route' => 'password.email']) }}

        <div class="form-group">
            {{ Form::email('email', old('email'), [ 'placeholder' => __('Email address'), 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Send password reset link') }}
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
        </div>

    {{ Form::close() }}

@endsection
