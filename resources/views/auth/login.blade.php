@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        @if(!empty($oauth_services))
            @foreach($oauth_services as $service)
                <div class="text-center mb-2">
                    <a href="{{ route('login.provider', $service['name']) }}" class="btn btn-secondary btn-block">
                        <x-icon :icon="$service['icon']" style="fab" class="mr-1"/>
                        {{ $service['label'] }}
                    </a>
                </div>
            @endforeach
            <div class="mt-3 mb-3 d-flex">
                <hr class="flex-fill">
                <div class="mx-3">{{ __('or') }}</div>
                <hr class="flex-fill">
            </div>
        @endif

        <div class="form-group">
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', empty($oauth_services) ? 'autofocus' : null, 'placeholder' => __('E-Mail Address') ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required', 'placeholder' => __('Password') ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
            <small><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></small>
        </div>

        {{ Form::bsCheckbox('remember', 1, false, __('Remember me')) }}

        <p class="mt-4">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Login') }}
            </button>
        </p>

        <div class="text-center mt-4">
            <span class="d-none d-sm-inline">{{ __('Are you new here?') }} <a href="{{ route('register') }}">{{ __('Create an account') }}</a></span>
            <span class="d-inline d-sm-none">{{ __('New here?') }} <a href="{{ route('register') }}">{{ __('Create account') }}</a></span>
        </div>

    {{ Form::close() }}

@endsection
