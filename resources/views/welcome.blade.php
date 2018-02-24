@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
    <h1 class="display-4">@lang('app.hello', [ 'name' => Auth::user()->name ])!</h1>
    <p class="lead">@lang('app.welcome_text', [ 'app_name' => Config::get('app.product_name') ]).</p>

    <div class="card-columns">
        @foreach($widgets as $w)
            {!! $w !!}
        @endforeach
    </div>


    <div class="card-columns">
        @include('dashboard.widgets.persons')
        @include('dashboard.widgets.bank')
        @include('dashboard.widgets.reports')
        @include('dashboard.widgets.tools')
    </div>

@endsection
