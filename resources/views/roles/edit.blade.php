@extends('layouts.app')

@section('title', 'Edit Role')

@section('buttons')
    <a href="{{ route('roles.show', $role) }}" class="btn btn-secondary"><i class="fa fa-search"></i> View Role</a>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a>
@endsection

@section('content')

    {!! Form::model($role, ['route' => ['roles.update', $role], 'method' => 'put']) !!}

        <div class="card mb-4">
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
                </div>

            </div>
        </div>

        {{ Form::button('<i class="fa fa-save"></i> Update', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}

@endsection
