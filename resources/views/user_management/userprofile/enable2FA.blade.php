@extends('layouts.app', ['wide_layout' => false])

@section('title', __('userprofile.tfa_authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.store2FA']]) !!}
        <div class="row">
            <div class="col-md">
                <p>@lang('userprofile.tfa_explanation')</p>
                <p>@lang('userprofile.tfa_apps')</p>
                <p>@lang('userprofile.tfa_scan_explanation')</p>
            </div>
            <div class="col-md-auto">
                <p class="text-center"><img src="data:image/png;base64,{{ $image }}" class="img-fluid" alt="QR Code"></p>
            </div>
        </div>

        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            <x-form.bs-submit-button :label="__('app.enable')"/>
        </p>
    {!! Form::close() !!}
@endsection
