@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <span class="pull-right">
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a>
    </span>

    <h1 class="display-4">Create User</h1>
	<br>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <i class="fa fa-warning"></i> Validation failed, you have entered invalid values!
        </div>
    @endif

    {!! Form::open(['route' => ['users.store']]) !!}
        <div class="card">
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

            </div>
        </div>
        <br>
        {{ Form::button('<i class="fa fa-save"></i> Create', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}

@endsection
