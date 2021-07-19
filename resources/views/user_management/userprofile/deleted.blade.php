@extends('layouts.login')

@section('title', 'Account Deletion')

@section('content')

    <x-alert type="info">
        {{ __('Your account has been deleted.') }}
    </x-alert>

     <div class="text-center mt-4">
        <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
    </div>

@endsection
