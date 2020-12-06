@extends('layouts.login')

@section('title', __('userprofile.login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <div class="form-group">
            {{-- {{ Form::label('email', __('userprofile.email')) }} --}}
            {{ Form::text('email', old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus', 'placeholder' => __('userprofile.email') ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{-- {{ Form::label('password', __('userprofile.password')) }} --}}
            {{ Form::password('password', ['class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required', 'placeholder' => __('userprofile.password') ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
            <small><a href="{{ route('password.request') }}">@lang('userprofile.forgot_your_password')</a></small>
        </div>

        {{ Form::bsCheckbox('remember', 1, false, __('userprofile.remember_me')) }}

        <p class="mt-4">
            <button type="submit" class="btn btn-primary btn-block">
                @lang('userprofile.login')
            </button>
        </p>

        @php
            $hasServices = collect(config('auth.socialite.drivers'))
                ->filter(fn ($driver) => config('services.' . $driver) !== null && array_elements_not_blank(config('services.' . $driver), ['client_id', 'client_secret', 'redirect']))
                ->isNotEmpty();
        @endphp
        @if($hasServices)
            <p class="text-center">@lang('app.or')</p>
            <p class="text-center">
            <div class="row">
                @foreach(config('auth.socialite.drivers') as $driver)
                    @if(config('services.' . $driver) !== null && array_elements_not_blank(config('services.' . $driver), ['client_id', 'client_secret', 'redirect']))
                        <div class="col-sm text-center mb-2">
                            <a href="{{ route('login.provider', $driver) }}" class="btn btn-secondary btn-sm btn-block">
                                <x-icon :icon="$driver" style="fab" class="mr-1"/>
                                {{ __('app.' . $driver . '_sign_in') }}
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="text-center mt-4">
            <span class="d-none d-sm-inline">@lang('userprofile.new_here') <a href="{{ route('register') }}">@lang('userprofile.crete_an_account')</a></span>
            <span class="d-inline d-sm-none">@lang('userprofile.new_here_short') <a href="{{ route('register') }}">@lang('userprofile.crete_an_account_short')</a></span>
        </div>

    {{ Form::close() }}

@endsection
