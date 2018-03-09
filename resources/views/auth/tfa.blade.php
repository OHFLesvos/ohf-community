@extends('layouts.login')

@section('title', __('userprofile.login'))

@section('content')

    {{ Form::open(['route' => 'login']) }}

        <p>@lang('userprofile.tfa_enter_code')</p>
        <div class="form-group">
            {{ Form::bsNumber('code', old('code'), [ 'required', 'autofocus' ], '') }}
        </div>
        {{ Form::hidden('email', request()->get('email')) }}
        {{ Form::hidden('password', request()->get('password')) }}
        {{ Form::hidden('remember', request()->get('remember')) }}
 
        <br>
        <button type="submit" class="btn btn-primary btn-block">
            @lang('userprofile.login')
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">@lang('app.cancel')</a>
        </div>

    {{ Form::close() }}

@endsection
