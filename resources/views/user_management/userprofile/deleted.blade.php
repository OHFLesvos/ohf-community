@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    <x-alert type="info">
        @lang('Your account has been deleted.')
    </x-alert>

     <div class="text-center mt-4">
        <a href="{{ route('login') }}">@lang('Return to login')</a>
    </div>

@endsection
