@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')

    @if (session()->has('login_message'))
        <x-alert type="success" icon="hand-paper">
            {!! session('login_message') !!}
        </x-alert>
    @endif

    @if(count($widgets) > 0)
        <div class="card-columns">
            @foreach($widgets as $widget)
                {!! $widget !!}
            @endforeach
        </div>
    @else
        <x-alert type="info">
            @lang('app.no_content_available_to_you')
        </x-alert>
    @endif

@endsection
