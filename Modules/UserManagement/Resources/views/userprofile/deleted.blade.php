@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    @component('components.alert.info')
        @lang('userprofile.account_deleted')
    @endcomponent

     <div class="text-center mt-4">
        <a href="{{ route('login') }}">@lang('userprofile.return_to_login')</a>
    </div>

@endsection
