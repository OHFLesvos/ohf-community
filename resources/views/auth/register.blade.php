@extends('layouts.login')

@section('title', __('Register'))

@section('content')

    <x-oauth-buttons signUp/>

    {{ Form::open(['route' => 'register']) }}

        <div class="form-group">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', old('name'), [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('email', __('E-Mail Address')) }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password', __('Password')) }}
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password_confirmation', __('Confirm password')) }}
            {{ Form::password('password_confirmation', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            {{ __('Register') }}
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
        </div>

    {{ Form::close() }}

@endsection
