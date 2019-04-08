@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')

    @if (session()->has('login_message'))
        <div class="alert alert-success">
        {{-- @component('components.alert.success') --}}
            @icon(hand-paper) {!! session('login_message') !!}
        {{-- @endcomponent --}}
        </div>
    @endif

    @if(count($widgets) > 0 )
        <div class="card-columns">
            @foreach($widgets as $widget)
                {!! $widget !!}
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            @lang('app.no_content_available_to_you')
        @endcomponent
    @endif

@endsection
