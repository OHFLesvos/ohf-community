@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
    <h1 class="display-4">@lang('app.hello', [ 'name' => Auth::user()->name ])!</h1>
    <p class="lead">@lang('app.welcome_text', [ 'app_name' => Config::get('app.product_name') ]).</p>

    @empty(Auth::user()->tfa_secret)
        @component('components.alert.info')
            @lang('userprofile.tfa_enable_recommendation', [ 'url' => route('userprofile.view2FA') ])
        @endcomponent
    @endempty

    @if ( count($widgets) > 0 )
        <div class="card-columns">
            @foreach($widgets as $widget => $args)
                @include($widget, $args)
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            @lang('app.no_content_available_to_you')
        @endcomponent
    @endif

@endsection
