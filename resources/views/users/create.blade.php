@extends('layouts.app')

@section('title', 'Create User')

@section('buttons')
    <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa fa-times-circle"></i> Cancel</a>
@endsection

@section('content')

    {!! Form::open(['route' => ['users.store']]) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">User Profile</div>
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
                                    {{ Form::password('password', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required',  'autocomplete' => 'new-password' ]) }}
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                    @endif
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

        {{ Form::button('<i class="fa fa-check"></i> Create', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}

@endsection
