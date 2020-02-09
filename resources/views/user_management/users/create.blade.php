@extends('layouts.app')

@section('title', __('app.create_user'))

@section('content')

    {!! Form::open(['route' => ['users.store']]) !!}

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
                                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsEmail('email', null, [ 'required' ], __('app.email')) }}
                            </div>
                            <div class="col-md">
                                {{ Form::bsPassword('password', [ 'required', 'autocomplete' => 'new-password' ], __('app.password')) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">@lang('app.roles')</div>
                    <div class="card-body">
                        {{ Form::bsCheckboxList('roles[]', $roles->mapWithKeys(function($role){return [ $role->id => $role->name ];}), null) }}
                        @empty($roles)
                            <em>@lang('app.no_roles_defined')</em>
                        @endempty
                        <hr>
                        {{ Form::bsCheckbox('is_super_admin', null, null, __('app.this_user_is_admin')) }}
                    </div>
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection
