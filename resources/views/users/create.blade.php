@extends('layouts.app')

@section('title', 'Create User')

@section('content')

    {!! Form::open(['route' => ['users.store']]) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">User Profile</div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('name', null, [ 'required', 'autofocus' ]) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('email', null, [ 'required' ], 'E-Mail') }}
                            </div>
                            <div class="col-md">
                                {{ Form::bsPassword('password', [ 'required' ]) }}
                            </div>
                        </div>

                        {{ Form::bsCheckbox('is_super_admin', null, null, 'This user is an administrator') }}

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">Roles</div>
                    <div class="card-body">
                        @forelse ($roles as $role)
                            <label>
                                {{ Form::checkbox('roles[]', $role->id) }} {{ $role->name }}
                            </label><br>
                        @empty
                            <em>No roles</em>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <p>
            {{ Form::bsSubmitButton('Create') }}
        </p>

    {!! Form::close() !!}

@endsection
