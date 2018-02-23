@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    @component('components.alert.info')
        @lang('userprofile.account_deleted')
    @endcomponent

    <p>
        {{ Form::bsButtonLink(route('login'), __('userprofile.go_to_login'), 'sign-in', 'info') }}
    </p>

@endsection
