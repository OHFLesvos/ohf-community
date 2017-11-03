@extends('layouts.app')

@section('title', 'Edit User')

@section('buttons')
    {{ Form::bsButtonLink(route('users.show', $user), 'Cancel', 'times-circle') }}
@endsection

@section('content')

    {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

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
                                {{ Form::bsPassword('password', [ ], null, 'Leave empty to keep current password.') }}
                            </div>
                        </div>

                        @if ( App\User::count() > 1 )
                            {{ Form::bsCheckbox('is_super_admin', null, null, 'This user is an administrator') }}
                        @endif

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
            {{ Form::bsSubmitButton('Update') }}
        </p>

    {!! Form::close() !!}

@endsection
