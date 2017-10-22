@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <span class="pull-right">
        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary"><i class="fa fa-search"></i> View user</a> &nbsp;
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a>
    </span>

    <h1 class="display-4">Edit User</h1>
	<br>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <i class="fa fa-warning"></i> Validation failed, you have entered invalid values!
        </div>
    @endif

    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">User profile</div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md">
                                <div class="form-group">
                                    {{ Form::label('name') }}
                                    {{ Form::text('name', null, [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    {{ Form::label('email', 'E-Mail') }}
                                    {{ Form::text('email', null, [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('password', 'Password') }}
                                    {{ Form::password('password', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'autocomplete' => 'new-password', 'aria-describedby' => 'passwordHelpBlock' ]) }}
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                    @endif
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Leave empty to keep current password.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                {{ Form::checkbox('is_super_admin', null, null, [ 'class' => 'form-check-input' ]) }}
                                This user is an administrator
                            </label>
                        </div>

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

        {{ Form::button('<i class="fa fa-save"></i> Update', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}

@endsection
