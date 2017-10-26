@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="display-4">Hello {{ Auth::user()->name }}!</h1>
    <p class="lead">Welcome to the {{ Config::get('app.product_name') }}.</p>

@endsection
