@extends('layouts.app')

@section('title', __('userprofile.tfa_authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.store2FA']]) !!}
        <p>@lang('userprofile.tfa_explanation')</p>
        <p>@lang('userprofile.tfa_apps')</p>
        <p class="text-center"><img src="data:image/png;base64,{{ $image }}" class="img-fluid"></p>
        <p>@lang('userprofile.tfa_scan_explanation')</p>
        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            {{ Form::bsSubmitButton(__('app.enable')) }}
        </p>
    {!! Form::close() !!}
@endsection
