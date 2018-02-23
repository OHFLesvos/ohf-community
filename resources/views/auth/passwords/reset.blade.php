@extends('layouts.login')

@section('title', __('userprofile.reset_password'))

@section('content')

    {{ Form::open(['route' => 'password.request']) }}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            {{ Form::label('email', __('userprofile.email')) }}
            {{ Form::text('email', $email or old('email'), [ 'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'required', 'autofocus' ]) }}
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password', __('userprofile.new_password')) }}
            {{ Form::password('password', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('password_confirmation', __('userprofile.confirm_password')) }}
            {{ Form::password('password_confirmation', [ 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''), 'required' ]) }}
            @if ($errors->has('password'))
                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <br>
        <button type="submit" class="btn btn-primary btn-block">
            @lang('userprofile.reset_password')
        </button>

    </form>
@endsection
