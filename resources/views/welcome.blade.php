@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
    <h1 class="display-4">@lang('app.hello', [ 'name' => Auth::user()->name ])!</h1>
    <p class="lead">@lang('app.welcome_text', [ 'app_name' => Config::get('app.product_name') ]).</p>

    @if ( count($widgets) > 0 )
        <div class="card-columns">
            @foreach($widgets as $w)
                {!! $w !!}
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            @lang('app.no_content_available_to_you')
        @endcomponent
    @endif

@endsection
