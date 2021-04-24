@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    <x-alert type="info">
        @lang('app.account_deleted')
    </x-alert>

     <div class="text-center mt-4">
        <a href="{{ route('login') }}">@lang('app.return_to_login')</a>
    </div>

@endsection
