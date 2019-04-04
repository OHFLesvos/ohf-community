@extends('layouts.app')

@section('title', __('changelog::changelog.changelog'))

@section('content')

    {!! $changelog !!}

@endsection
