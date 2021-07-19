@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Two-Factor Authentication'))

@section('content')
    {!! Form::open(['route' => ['userprofile.store2FA']]) !!}
        <div class="row">
            <div class="col-md align-self-center">
                <p>{{ __('Two-Factor Authentication improves the security of your account by requiring an additional code when logging in. This random code
    is being regenerated every minute on a second device (e.g. your Android or iOS-based smartphone). Therefore, even if your password falls into the wrong hands,
    a second factor is still required to login successfully into this application.') }}</p>
                <p>{{ __('A mobile app is required to generate the Two-Factor code. Such apps can be found in the app store of your mobile device. We recommend
    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator</a>,
    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.authy.authy">Authy</a> or
    <a target="_blank" href="https://play.google.com/store/apps/details?id=org.fedorahosted.freeotp">FreeOTP Authenticator</a>.') }}</p>
                <p>{{ __('Scan the QR code with your authenticator app (e.g. "Google-Authenticator") and enter the numeric code into the field below.') }}</p>
            </div>
            <div class="col-md-auto">
                <p class="text-center"><img src="data:image/png;base64,{{ $image }}" class="img-fluid" alt="QR Code"></p>
            </div>
        </div>

        {{  Form::bsNumber('code', null, [ 'required', 'autofocus' ], '') }}
        <p>
            <x-form.bs-submit-button :label="__('Enable')"/>
        </p>
    {!! Form::close() !!}
@endsection
