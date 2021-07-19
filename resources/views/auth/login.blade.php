@extends('layouts.login')

@section('title', __('Login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <div class="form-group">
            {{-- {{ Form::label('email', __('E-Mail Address')) }} --}}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus', 'placeholder' => __('E-Mail Address') ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{-- {{ Form::label('password', __('Password')) }} --}}
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

        @php
            $hasServices = collect(config('auth.socialite.drivers'))
                ->filter(fn ($driver) => config('services.' . $driver) !== null && array_elements_not_blank(config('services.' . $driver), ['client_id', 'client_secret', 'redirect']))
                ->isNotEmpty();
        @endphp
        @if($hasServices)
            <p class="text-center">{{ __('or') }}</p>
            <p class="text-center">
            <div class="row">
                @foreach(config('auth.socialite.drivers') as $driver)
                    @if(config('services.' . $driver) !== null && array_elements_not_blank(config('services.' . $driver), ['client_id', 'client_secret', 'redirect']))
                        <div class="col-sm text-center mb-2">
                            <a href="{{ route('login.provider', $driver) }}" class="btn btn-secondary btn-sm btn-block">
                                <x-icon :icon="$driver" style="fab" class="mr-1"/>
                                @if($driver == 'google')
                                    {{ __('Sign in with Google') }}
                                @endif
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="text-center mt-4">
            <span class="d-none d-sm-inline">{{ __('Are you new here?') }} <a href="{{ route('register') }}">{{ __('Create an account') }}</a></span>
            <span class="d-inline d-sm-none">{{ __('New here?') }} <a href="{{ route('register') }}">{{ __('Create account') }}</a></span>
        </div>

    {{ Form::close() }}

@endsection
