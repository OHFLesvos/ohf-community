@extends('layouts.app')

@section('title', __('userprofile.title'))

@section('content')

    <div class="card-columns">

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.profile')</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.update']]) !!}
                        {{ Form::bsText('name', $user->name, [ 'required' ], __('userprofile.name')) }}
                        {{ Form::bsEmail('email', $user->email, [ ! empty($user->provider_name) ? 'disabled' : 'required' ], __('userprofile.email')) }}
                        <x-form.bs-submit-button :label="__('userprofile.update')"/>
                    {!! Form::close() !!}
                </div>
            </div>

            @if(empty($user->provider_name))
                <div class="card mb-4">
                    <div class="card-header">@lang('userprofile.change_password')</div>
                    <div class="card-body">
                        {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}
                            {{ Form::bsPassword('old_password', [ 'required' ], __('userprofile.old_password')) }}
                            {{ Form::bsPassword('password', [ 'required' ], __('userprofile.new_password')) }}
                            {{ Form::bsPassword('password_confirmation', [ 'required' ], __('userprofile.confirm_password')) }}
                            <x-form.bs-submit-button :label="__('userprofile.update_password')"/>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">@lang('userprofile.tfa_authentication')</div>
                    <div class="card-body">
                        @empty($user->tfa_secret)
                            <x-alert type="info">
                                @lang('userprofile.tfa_enable_recommendation', [ 'url' => route('userprofile.view2FA') ])
                            </x-alert>
                            <x-alert type="warning">
                                @lang('userprofile.tfa_authentication_not_enabled')
                            </x-alert>
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="check"/> @lang('app.enable')
                            </a>
                        @else
                            <p>@lang('userprofile.tfa_authentication_enabled')</p>
                            <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">
                                <x-icon icon="times"/> @lang('app.disable')
                            </a>
                        @endempty
                    </div>
                </div>
            @endunless

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.language')</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach (language()->allowed() as $code => $name)
                            <li class="list-group-item">
                                @if(App::getLocale() == $code)
                                    <x-icon icon="check" class="text-success"/>
                                @else
                                    <span class="d-inline-block" style="width: 1em"></span>
                                @endif
                                <a href="{{ language()->back($code) }}">
                                    {{ $name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
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

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.account_removal')</div>
                <div class="card-body">
                    <p>@lang('userprofile.account_remove_desc')</p>
                    {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                        <x-form.bs-delete-button
                            :label="__('userprofile.delete_account')"
                            icon="user-times"
                            :confirmation="__('userprofile.delete_confirm')"
                        />
                    {!! Form::close() !!}
                </div>
            </div>

        </div>

@endsection
