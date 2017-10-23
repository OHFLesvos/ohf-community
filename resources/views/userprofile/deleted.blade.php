@extends('layouts.app')

@section('title', 'User Profile')

@section('content')

    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Your account has been deleted.
    </div>

    <p><a href="{{ route('login') }}" class="btn btn-info">Go to Login</a></p>

@endsection
