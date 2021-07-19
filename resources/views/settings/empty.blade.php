@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Settings'))

@section('content')
    <x-alert type="info">
        {{ __('There is currently no content available for you here.') }}
    </x-alert>
@endsection
