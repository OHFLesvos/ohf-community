@extends('layouts.app')

@section('title', __('User Profile'))

@section('content')

    <div class="card-columns">

            {!! Form::open(['route' => ['userprofile.update']]) !!}
            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ __('Profile') }}</div>
                <div class="card-body pb-2">
                    {{ Form::bsText('name', $user->name, [ 'required' ], __('Name')) }}
                    {{ Form::bsEmail('email', $user->email, [ ! empty($user->provider_name) ? 'disabled' : 'required' ], __('E-Mail Address')) }}
                </div>
                <div class="card-footer text-right">
                    <x-form.bs-submit-button :label="__('Update')"/>
                </div>
            </div>
            {!! Form::close() !!}

            @if(empty($user->provider_name))
                {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">{{ __('Change Password') }}</div>
                        <div class="card-body pb-2">
                            {{ Form::bsPassword('old_password', [ 'required' ], __('Old Password')) }}
                            {{ Form::bsPassword('password', [ 'required' ], __('New password')) }}
                            {{ Form::bsPassword('password_confirmation', [ 'required' ], __('Confirm password')) }}
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('Update password')"/>
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="card shadow-sm mb-4">
                    <div class="card-header">{{ __('Two-Factor Authentication') }}</div>
                    @empty($user->tfa_secret)
                        <div class="card-body pb-1">
                            <x-alert type="info">
                                {{ __('Improve the security of your account by <a href=":url">enabling Two-Factor Authentication</a>.', [ 'url' => route('userprofile.view2FA') ]) }}
                            </x-alert>
                            <x-alert type="warning">
                                {{ __('Two-Factor Authentication is not enabled.') }}
                            </x-alert>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="check"/> {{ __('Enable') }}
                            </a>
                        </div>
                    @else
                        <div class="card-body pb-1">
                            <p>{{ __('Two-Factor Authentication is enabled') }}</p>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="times"/> {{ __('Disable') }}
                            </a>
                        </div>
                    @endempty
                </div>
            @endunless

            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ __('Language') }}</div>
                <div class="list-group list-group-flush">
                    @foreach (language()->allowed() as $code => $name)
                        <a class="list-group-item list-group-item-action" href="{{ language()->back($code) }}">
                            @if(App::getLocale() == $code)
                                <x-icon icon="check" class="text-success" class="mr-1"/>
                            @else
                                <span class="d-inline-block mr-1" style="width: 1em"></span>
                            @endif
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">{{ __('Account Information') }}</div>
                <div class="card-body pb-2">
                    <p>
                        <x-user-avatar :user="Auth::user()" size="80"/>
                    </p>
                    <p>{{ __('Your account has been created on') }} <strong>{{ $user->created_at }}</strong>
                        {{ __('and last updated on') }} <strong>{{ $user->updated_at }}</strong>.
                    </p>
                    @if (! $user->roles->isEmpty())
                        <p>{{ __('Your roles') }}:</p>
                        <ul>
                            @foreach ($user->roles->sortBy('name') as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @isset($user->provider_name)
                        <p>{{ __('OAuth provider') }}: {{ $user->provider_name }}</p>
                    @endisset
                </div>
            </div>

            {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">{{ __('Account Removal') }}</div>
                    <div class="card-body pb-1">
                        <p>{{ __('If you no longer plan to use this service, you can remove your account and delete all associated data.') }}</p>
                    </div>
                    <div class="card-footer text-right">
                        <x-form.bs-delete-button
                            :label="__('Delete account')"
                            icon="user-times"
                            :confirmation="__('Do you really want to delete your account and lose access to all data?')"
                        />
                    </div>
                </div>
            {!! Form::close() !!}

        </div>

@endsection
