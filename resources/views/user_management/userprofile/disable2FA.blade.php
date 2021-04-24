@extends('layouts.app', ['wide_layout' => false])

@section('title', __('app.tfa_authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.disable2FA'], 'method' => 'delete']) !!}
        <p>@lang('app.tfa_enter_code')</p>
        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            <x-form.bs-submit-button :label="__('app.disable')" icon="times"/>
        </p>
    {!! Form::close() !!}
@endsection
