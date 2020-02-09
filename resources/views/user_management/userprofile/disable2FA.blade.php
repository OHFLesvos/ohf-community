@extends('layouts.app')

@section('title', __('userprofile.tfa_authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.disable2FA'], 'method' => 'delete']) !!}
        <p>@lang('userprofile.tfa_enter_code')</p>
        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            {{ Form::bsSubmitButton(__('app.disable'), 'times') }}
        </p>
    {!! Form::close() !!}
@endsection
