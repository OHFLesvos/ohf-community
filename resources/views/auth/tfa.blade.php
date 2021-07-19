@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <p>{{ __('Enter the code from your authenticator app into the field below.') }}</p>
        <div class="form-group">
            {{ Form::bsNumber('code', old('code'), [ 'required', 'autofocus' ], '') }}
        </div>
        {{ Form::hidden('email', request()->get('email')) }}
        {{ Form::hidden('password', request()->get('password')) }}
        {{ Form::hidden('remember', request()->get('remember')) }}

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            {{ __('Login') }}
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">{{ __('Cancel') }}</a>
        </div>

    {{ Form::close() }}

@endsection
