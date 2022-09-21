@extends('layouts.login', [
    'width' => 600,
])

@section('title', __('Privacy Policy'))

@section('content')
    @isset($content)
        {!! $content !!}
    @else
        {{ __('There is currently no content available for you here.') }}
    @endisset
@endsection
