@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Two-Factor Authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.disable2FA'], 'method' => 'delete']) !!}
        <p>{{ __('Enter the code from your authenticator app into the field below.') }}</p>
        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            <x-form.bs-submit-button :label="__('Disable')" icon="times"/>
        </p>
    {!! Form::close() !!}
@endsection
