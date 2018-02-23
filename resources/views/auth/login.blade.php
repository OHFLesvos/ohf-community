@extends('layouts.login')

@section('title', __('userprofile.login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <div class="form-group">
            {{ Form::label('email', __('userprofile.email')) }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password', __('userprofile.password')) }}
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
            <small><a href="{{ route('password.request') }}">@lang('userprofile.forgot_your_password')</a></small>
        </div>
 
        <br>
        <button type="submit" class="btn btn-primary btn-block">
            @lang('userprofile.login')
        </button>
        <input type="hidden" name="remember" value="1"/>

        <div class="text-center mt-4">
            @lang('userprofile.new_here') <a href="{{ route('register') }}">@lang('userprofile.crete_an_account')</a>
        </div>

    {{ Form::close() }}

@endsection
