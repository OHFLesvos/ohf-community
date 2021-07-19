@extends('layouts.login')

@section('title', __('Privacy Policy'))

@section('content')
    @isset($content)
        {!! $content !!}
    @else
        {{ __('There is currently no content available for you here.') }}
    @endisset
@endsection
