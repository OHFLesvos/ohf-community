@extends('layouts.app')

@section('title', __('Dashboard'))

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
            {{ __('There is currently no content available for you here.')  }}
        </x-alert>
    @endif
@endsection
