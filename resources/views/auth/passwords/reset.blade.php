@extends('layouts.login')

@section('content')

    <h1 class="display-4 text-center">Reset password</h1>
    <br>

    {{ Form::open(['route' => 'password.request']) }}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            {{ Form::label('email', 'E-Mail Address') }}
            {{ Form::text('email', $email or old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
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

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            Reset Password
        </button>

    </form>
@endsection
