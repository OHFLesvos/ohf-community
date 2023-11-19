@extends('layouts.login')

@section('title', __('Reset password'))

@section('content')

    <form method="POST" action="{{ route('password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">{{ __('Email address') }}</label>
            <input name="email" id="email" type="email" value="{{ $email ?? old('email') }}" class="form-control @error('email') is-invalid @enderror" readonly>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input name="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" required autofocus autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirm password') }}</label>
            <input name="password_confirmation" id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            {{ __('Reset password') }}
        </button>

    </form>
@endsection
