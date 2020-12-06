@extends('layouts.login')

@section('title', __('userprofile.reset_password'))

@section('content')

    @if (session('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <p>@lang('userprofile.reset_password_instructions')</p>

    {{ Form::open(['route' => 'password.email']) }}

        <div class="form-group">
            {{ Form::label('email', __('userprofile.email')) }}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            @lang('userprofile.send_reset_password_link')
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">@lang('userprofile.return_to_login')</a>
        </div>

    {{ Form::close() }}

@endsection
