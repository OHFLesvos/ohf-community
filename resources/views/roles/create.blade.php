@extends('layouts.app')

@section('title', 'Create Role')

@section('content')

    {!! Form::open(['route' => ['roles.store']]) !!}

        <div class="row">

            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">Role</div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="col-md">
                                {{ Form::bsText('name', null, [ 'required', 'autofocus' ]) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">Permissions</div>
                    <div class="card-body">

                        @forelse ($permissions as $k => $v)
                            <label>
                                {{ Form::checkbox('permissions[]', $k) }} {{ $v }}
                            </label><br>
                        @empty
                            <em>No permissions</em>
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
