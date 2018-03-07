@extends('layouts.app')

@section('title', __('app.edit_user'))

@section('content')

    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

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
                                {{ Form::bsText('email', null, [ 'required' ], __('app.email')) }}
                            </div>
                            <div class="col-md">
                                {{ Form::bsPassword('password', [ ], __('app.password'), __('app.leave_empty_to_keep_current_password')) }}
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
                            <em>@lang('app.no_roles')</em>
                        @endempty
                        @if ( App\User::count() > 1 )
                            <hr>
                            {{ Form::bsCheckbox('is_super_admin', null, null, __('app.this_user_is_admin')) }}
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
