@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Edit User'))

@section('content')
    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ __('User Profile') }}</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('name', null, [
                                $user->provider_name !== null ? 'readonly' : 'required'
                            ],
                            __('Name'),
                            $user->provider_name !== null ? __('Managed by :provider', ['provider' => ucfirst($user->provider_name)]) : null
                        ) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsEmail('email', null, [
                                $user->provider_name !== null ? 'readonly' : 'required'
                            ],
                            __('Email address'),
                            $user->provider_name !== null ? __('Managed by :provider', ['provider' => ucfirst($user->provider_name)]) : null
                        ) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsPassword('password', [
                                $user->provider_name !== null ? 'disabled' : null,
                                'autocomplete' => 'new-password'
                            ],
                            __('Password'),
                            $user->provider_name !== null ? __('Managed by :provider', ['provider' => ucfirst($user->provider_name)]) : __('Leave empty to keep current password.')
                        ) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ __('Roles') }}</div>
            <div class="card-body">
                <div class="columns-2">
                    {{ Form::bsCheckboxList('roles[]', $roles->mapWithKeys(fn ($role) => [ $role->id => $role->name ]), null) }}
                </div>
                @empty($roles)
                    <em>{{ __('No roles defined.') }}</em>
                @endempty
                @if (App\Models\User::count() > 1)
                    <hr>
                    {{ Form::bsCheckbox('is_super_admin', true, null, __('This user is an administrator')) }}
                @endif
            </div>
        </div>

        <p class="text-right">
            <x-form.bs-submit-button :label="__('Update')"/>
        </p>

    {!! Form::close() !!}
@endsection
