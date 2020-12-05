@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')
    <x-alert type="info">
        @lang('app.no_content_available_to_you')
    </x-alert>
@endsection
