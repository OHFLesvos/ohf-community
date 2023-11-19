@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <p>{{ __('Enter the code from your authenticator app into the field below.') }}</p>

        <div class="form-group">
            <input name="code" type="text" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" required autofocus>
            @error('code')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="email" value="{{ request()->get('email') }}">
        <input type="hidden" name="password" value="{{ request()->get('password') }}">
        <input type="hidden" name="remember" value="{{ request()->get('remember') }}">

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            {{ __('Login') }}
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">{{ __('Cancel') }}</a>
        </div>

    </form>

@endsection
