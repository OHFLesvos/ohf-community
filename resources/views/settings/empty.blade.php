@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    @component('components.alert.info')
        @lang('app.no_content_available_to_you')
    @endcomponent

@endsection
