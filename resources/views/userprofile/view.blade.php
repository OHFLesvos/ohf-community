@extends('layouts.app')

@section('title', 'User Profile')

@section('content')

    <div class="card-columns">

            <div class="card">
                <div class="card-header">Profile</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.update']]) !!}
                        <div class="form-group">
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', $user->name, [ 'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', 'E-Mail Address') }}
                            {{ Form::text('email', $user->email, [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        {{ Form::button('<i class="fa fa-check"></i> Update', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    {!! Form::open(['route' => ['userprofile.updatePassword']]) !!}

                        <div class="form-group">
                            {{ Form::label('old_password', 'Old Password') }}
                            {{ Form::password('old_password', [ 'class' => 'form-control'.($errors->has('old_password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('old_password'))
                                <span class="invalid-feedback">{{ $errors->first('old_password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('password', 'New Password') }}
                            {{ Form::password('password', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('password_confirmation', 'Confirm new Password') }}
                            {{ Form::password('password_confirmation', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-check"></i> Update password', [ 'type' => 'submit', 'class' => 'btn btn-primary' ]) }}
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="card">
                <div class="card-header">Account Information</div>
                <div class="card-body">
                    <p>Your account has been created on <strong>{{ $user->created_at }}</strong> 
                        and last updated on <strong>{{ $user->updated_at }}</strong>.</p>
                    @if ( ! $user->roles->isEmpty() )
                        <p>Your roles:
                            @foreach ($user->roles->sortBy('name') as $role)
                                {{ $role->name }}@if (! $loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">Account Removal</div>
                <div class="card-body">
                    <p>If you no longer plan to use this service, you can remove your account and delete all associated data.</p>
                    {!! Form::open(['route' => ['userprofile.delete'], 'method' => 'delete']) !!}
                        {{ Form::button('<i class="fa fa-user-times"></i> Delete account', [ 'type' => 'submit', 'class' => 'btn btn-danger', 'id' => 'delete_account_button' ]) }}
                    {!! Form::close() !!}
                </div>
            </div>

        </div>

@endsection

@section('script')
    $( '#delete_account_button' ).on('click', function(){
        return confirm('Do you really want to delete your account and lose access to all data?');
    });
@endsection
