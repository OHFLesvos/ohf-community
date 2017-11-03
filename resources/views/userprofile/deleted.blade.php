@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    @component('components.alert.info')
        Your account has been deleted.
    @endcomponent

    <p>
        {{ Form::bsButtonLink(route('login'), 'Go to Login', 'sign-in', 'info') }}
    </p>

@endsection
