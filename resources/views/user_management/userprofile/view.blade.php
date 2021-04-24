@extends('layouts.app')

@section('title', __('app.user_profile'))

@section('content')

    <div class="card-columns">

            {!! Form::open(['route' => ['userprofile.update']]) !!}
            <div class="card shadow-sm mb-4">
                <div class="card-header">@lang('app.profile')</div>
                <div class="card-body pb-2">
                    {{ Form::bsText('name', $user->name, [ 'required' ], __('app.name')) }}
                    {{ Form::bsEmail('email', $user->email, [ ! empty($user->provider_name) ? 'disabled' : 'required' ], __('app.email')) }}
                </div>
                <div class="card-footer text-right">
                    <x-form.bs-submit-button :label="__('app.update')"/>
                </div>
            </div>
            {!! Form::close() !!}

            @if(empty($user->provider_name))
                {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('app.change_password')</div>
                        <div class="card-body pb-2">
                            {{ Form::bsPassword('old_password', [ 'required' ], __('app.old_password')) }}
                            {{ Form::bsPassword('password', [ 'required' ], __('app.new_password')) }}
                            {{ Form::bsPassword('password_confirmation', [ 'required' ], __('app.confirm_password')) }}
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('app.update_password')"/>
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="card shadow-sm mb-4">
                    <div class="card-header">@lang('app.tfa_authentication')</div>
                    @empty($user->tfa_secret)
                        <div class="card-body pb-1">
                            <x-alert type="info">
                                @lang('app.tfa_enable_recommendation', [ 'url' => route('userprofile.view2FA') ])
                            </x-alert>
                            <x-alert type="warning">
                                @lang('app.tfa_authentication_not_enabled')
                            </x-alert>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="check"/> @lang('app.enable')
                            </a>
                        </div>
                    @else
                        <div class="card-body pb-1">
                            <p>@lang('app.tfa_authentication_enabled')</p>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="times"/> @lang('app.disable')
                            </a>
                        </div>
                    @endempty
                </div>
            @endunless

            <div class="card shadow-sm mb-4">
                <div class="card-header">@lang('app.language')</div>
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
                <div class="card-header">@lang('app.account_information')</div>
                <div class="card-body pb-2">
                    <p>
                        <x-user-avatar :user="Auth::user()" size="80"/>
                    </p>
                    <p>@lang('app.account_created_on') <strong>{{ $user->created_at }}</strong>
                        @lang('app.account_updated_on') <strong>{{ $user->updated_at }}</strong>.
                    </p>
                    @if (! $user->roles->isEmpty())
                        <p>@lang('app.your_roles'):</p>
                        <ul>
                            @foreach ($user->roles->sortBy('name') as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @isset($user->provider_name)
                        <p>@lang('app.oauth_provider'): {{ $user->provider_name }}</p>
                    @endisset
                </div>
            </div>

            {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">@lang('app.account_removal')</div>
                    <div class="card-body pb-1">
                        <p>@lang('app.account_remove_desc')</p>
                    </div>
                    <div class="card-footer text-right">
                        <x-form.bs-delete-button
                            :label="__('app.delete_account')"
                            icon="user-times"
                            :confirmation="__('app.delete_confirm')"
                        />
                    </div>
                </div>
            {!! Form::close() !!}

        </div>

@endsection
