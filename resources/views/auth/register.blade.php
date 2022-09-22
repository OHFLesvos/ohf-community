@extends('layouts.login')

@section('title', __('Create an account'))

@section('content')

    <x-oauth-buttons signUp/>

    {{ Form::open(['route' => 'register']) }}

        <div class="form-group">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', old('name'), [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('email', __('Email address')) }}
            {{ Form::email('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password', __('Password')) }}
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required', 'autocomplete' => 'new-password' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password_confirmation', __('Confirm password')) }}
            {{ Form::password('password_confirmation', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required', 'autocomplete' => 'new-password' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Register') }}
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="d-block">{{ __('Already have an account? Log in') }}</a>
        </div>

    {{ Form::close() }}

@endsection
