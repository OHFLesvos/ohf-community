@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Create User'))

@section('content')
    {!! Form::open(['route' => ['users.store']]) !!}

        <!-- The text and password here are to prevent FF from auto filling my login credentials because it ignores autocomplete="off"-->
        <input type="text" style="display:none">
        <input type="password" style="display:none">

        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('User Profile')</div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('Name')) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md">
                        {{ Form::bsEmail('email', null, [ 'required' ], __('E-Mail Address')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsPassword('password', [ 'required', 'autocomplete' => 'new-password' ], __('Password')) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('Roles')</div>
            <div class="card-body">
                <div class="columns-2">
                    {{ Form::bsCheckboxList('roles[]', $roles->mapWithKeys(fn ($role) => [ $role->id => $role->name ]), null) }}
                </div>
                @empty($roles)
                    <em>@lang('No roles defined.')</em>
                @endempty
                <hr>
                {{ Form::bsCheckbox('is_super_admin', true, null, __('This user is an administrator')) }}
            </div>
        </div>

        <p class="text-right">
            <x-form.bs-submit-button :label="__('Create')"/>
        </p>
    {!! Form::close() !!}
@endsection
