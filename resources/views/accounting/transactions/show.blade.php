@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Show transaction'))

@section('content')

    <div id="app">
        <x-spinner />
    </div>

    @include('accounting.transactions.snippet')

@endsection
