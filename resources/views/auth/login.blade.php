@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-oauth-buttons/>

        <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required placeholder="{{ __('Email address') }}"/>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="{{ __('Password') }}"/>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="remember" value="1"/>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Login') }}
            </button>
        </div>

        <div class="d-sm-flex justify-content-between">
            <a href="{{ route('register') }}" class="d-block">{{ __('No account? Sign up') }}</a>
            <a href="{{ route('password.request') }}" class="d-block">{{ __('Forgot password?') }}</a>
        </div>

    </form>

@endsection
