@extends('layouts.login')

@section('title', __('Reset password'))

@section('content')

    @if (session('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <p>@lang('Please enter your e-mail address. We will send you a link which allows you to reset your password.')</p>

    {{ Form::open(['route' => 'password.email']) }}

        <div class="form-group">
            {{ Form::label('email', __('E-Mail Address')) }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            @lang('Send password reset link')
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">@lang('Return to login')</a>
        </div>

    {{ Form::close() }}

@endsection
