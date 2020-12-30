@extends('layouts.app')

@section('title', __('userprofile.title'))

@section('content')

    <div class="card-columns">

            {!! Form::open(['route' => ['userprofile.update']]) !!}
            <div class="card shadow-sm mb-4">
                <div class="card-header">@lang('userprofile.profile')</div>
                <div class="card-body pb-2">
                    {{ Form::bsText('name', $user->name, [ 'required' ], __('userprofile.name')) }}
                    {{ Form::bsEmail('email', $user->email, [ ! empty($user->provider_name) ? 'disabled' : 'required' ], __('userprofile.email')) }}
                </div>
                <div class="card-footer text-right">
                    <x-form.bs-submit-button :label="__('userprofile.update')"/>
                </div>
            </div>
            {!! Form::close() !!}

            @if(empty($user->provider_name))
                {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('userprofile.change_password')</div>
                        <div class="card-body pb-2">
                            {{ Form::bsPassword('old_password', [ 'required' ], __('userprofile.old_password')) }}
                            {{ Form::bsPassword('password', [ 'required' ], __('userprofile.new_password')) }}
                            {{ Form::bsPassword('password_confirmation', [ 'required' ], __('userprofile.confirm_password')) }}
                        </div>
                        <div class="card-footer text-right">
                            <x-form.bs-submit-button :label="__('userprofile.update_password')"/>
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="card shadow-sm mb-4">
                    <div class="card-header">@lang('userprofile.tfa_authentication')</div>
                    @empty($user->tfa_secret)
                        <div class="card-body pb-1">
                            <x-alert type="info">
                                @lang('userprofile.tfa_enable_recommendation', [ 'url' => route('userprofile.view2FA') ])
                            </x-alert>
                            <x-alert type="warning">
                                @lang('userprofile.tfa_authentication_not_enabled')
                            </x-alert>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="check"/> @lang('app.enable')
                            </a>
                        </div>
                    @else
                        <div class="card-body pb-1">
                            <p>@lang('userprofile.tfa_authentication_enabled')</p>
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
                <div class="card-header">@lang('userprofile.language')</div>
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
                <div class="card-header">@lang('userprofile.account_information')</div>
                <div class="card-body pb-2">
                    <p>
                        <x-user-avatar :user="Auth::user()" size="80"/>
                    </p>
                    <p>@lang('userprofile.account_created_on') <strong>{{ $user->created_at }}</strong>
                        @lang('userprofile.account_updated_on') <strong>{{ $user->updated_at }}</strong>.
                    </p>
                    @if (! $user->roles->isEmpty())
                        <p>@lang('userprofile.your_roles'):</p>
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
                    <div class="card-header">@lang('userprofile.account_removal')</div>
                    <div class="card-body pb-1">
                        <p>@lang('userprofile.account_remove_desc')</p>
                    </div>
                    <div class="card-footer text-right">
                        <x-form.bs-delete-button
                            :label="__('userprofile.delete_account')"
                            icon="user-times"
                            :confirmation="__('userprofile.delete_confirm')"
                        />
                    </div>
                </div>
            {!! Form::close() !!}

        </div>

@endsection
