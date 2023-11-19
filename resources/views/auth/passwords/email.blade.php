@extends('layouts.login')

@section('title', __('Reset password'))

@section('content')

    @if (session('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <p>{{ __('Please enter your email address. We will send you a link which allows you to reset your password.') }}</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="{{ __('Email address') }}">
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Send password reset link') }}
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
        </div>

    </form>

@endsection
