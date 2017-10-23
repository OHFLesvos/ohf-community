@extends('layouts.app')

@section('title', 'Create Role')

@section('buttons')
    <a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Overview</a>
@endsection

@section('content')

    {!! Form::open(['route' => ['roles.store']]) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">Role</div>
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
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">Permissions</div>
                    <div class="card-body">
                        @forelse ($permissions as $permission)
                            <label>
                                {{ Form::checkbox('permissions[]', $permission->id) }} {{ $permission->name }}
                            </label><br>
                        @empty
                            <em>No permissions</em>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{ Form::button('<i class="fa fa-save"></i> Create', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }} &nbsp;
    {!! Form::close() !!}

@endsection
