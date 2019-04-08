@extends('layouts.app')

@section('title', __('userprofile.title'))

@section('content')

    <div class="card-columns">

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.profile')</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.update']]) !!}
                        {{ Form::bsText('name', $user->name, [ 'required' ], __('userprofile.name')) }}
                        {{ Form::bsEmail('email', $user->email, [ 'required' ], __('userprofile.email')) }}
                        {{ Form::bsSubmitButton(__('userprofile.update')) }}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.change_password')</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}
                        {{ Form::bsPassword('old_password', [ 'required' ], __('userprofile.old_password')) }}
                        {{ Form::bsPassword('password', [ 'required' ], __('userprofile.new_password')) }}
                        {{ Form::bsPassword('password_confirmation', [ 'required' ], __('userprofile.confirm_password')) }}
                        {{ Form::bsSubmitButton(__('userprofile.update_password')) }}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.tfa_authentication')</div>
                <div class="card-body">
                    @empty($user->tfa_secret)
                        @component('components.alert.warning')
                            @lang('userprofile.tfa_authentication_not_enabled')
                        @endcomponent
                        <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">@icon(check) @lang('app.enable')</a>
                    @else
                        <p>@lang('userprofile.tfa_authentication_enabled')</p>
                        <a href="{{ route('userprofile.view2FA') }}" class="btn btn-primary">@icon(times) @lang('app.disable')</a>
                    @endempty
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.avatar')</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <img src="{{ Auth::user()->avatarUrl('userprofile') }}" alt="Gravatar" class="img-responsive">
                        </div>
                        <div class="col align-self-center">
                            <p><a href="https://gravatar.com/emails/" target="_blank" class="btn btn-secondary">@icon(sync) @lang('userprofile.change_picture')</a></p>
                            @lang('userprofile.avatars_provided_by_gravatar')
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.language')</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach (language()->allowed() as $code => $name)
                            <a href="{{ language()->back($code) }}" class="list-group-item">
                                @if( App::getLocale() == $code )
                                    <span class="text-success">@icon(check)</span>
                                @else
                                    <span class="d-inline-block" style="width: 1em"></span>
                                @endif
                                {{ $name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.account_information')</div>
                <div class="card-body pb-2">
                    <p>@lang('userprofile.account_created_on') <strong>{{ $user->created_at }}</strong> 
                        @lang('userprofile.account_updated_on') <strong>{{ $user->updated_at }}</strong>.</p>
                    @if ( ! $user->roles->isEmpty() )
                        <p>@lang('userprofile.your_roles'):
                            @foreach ($user->roles->sortBy('name') as $role)
                                {{ $role->name }}@if (! $loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('userprofile.account_removal')</div>
                <div class="card-body">
                    <p>@lang('userprofile.account_remove_desc')</p>
                    {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                        {{ Form::bsDeleteButton(__('userprofile.delete_account'), 'user-times', __('userprofile.delete_confirm')) }}
                    {!! Form::close() !!}
                </div>
            </div>

        </div>

@endsection
