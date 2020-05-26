@extends('layouts.app')

@section('title', __('app.edit_user'))

@section('content')

    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

        <!-- The text and password here are to prevent FF from auto filling my login credentials because it ignores autocomplete="off"-->
        <input type="text" style="display:none">
        <input type="password" style="display:none">

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">@lang('app.user_profile')</div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsEmail('email', null, [ ! empty($user->provider_name) ? 'disabled' : 'required' ], __('app.email')) }}
                            </div>
                            <div class="col-md">
                                {{ Form::bsPassword('password', [ ! empty($user->provider_name) ? 'disabled' : null ], __('app.password'), __('app.leave_empty_to_keep_current_password')) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">@lang('app.roles')</div>
                    <div class="card-body">
                        {{ Form::bsCheckboxList('roles[]', $roles->mapWithKeys(fn ($role) => [ $role->id => $role->name ]), null) }}
                        @empty($roles)
                            <em>@lang('app.no_roles_defined')</em>
                        @endempty
                        @if (App\User::count() > 1)
                            <hr>
                            {{ Form::bsCheckbox('is_super_admin', true, null, __('app.this_user_is_admin')) }}
                        @endif

                    </div>
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
