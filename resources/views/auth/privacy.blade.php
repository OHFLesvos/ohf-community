@extends('layouts.login')

@section('title', __('app.privacy_policy'))

@section('content')
    @isset($content)
        {!! $content !!}
    @else
        @lang('app.no_content_available_to_you')
    @endisset
@endsection
