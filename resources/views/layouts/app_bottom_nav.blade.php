@php
    $content_padding = 'p-0';
    $fab_class = 'floating-action-button-elevated';
@endphp
@extends('layouts.app')

@section('title', __('people.bank'))

@section('content')
    <div class="d-flex flex-column h-100" style="margin: 0; padding: 0">
        <div style="flex: 1; overflow-y: auto" class="p-3">
            @yield('wrapped-content')
        </div>
        <div style="flex: 0 0  auto; box-shadow: lightgrey 0px 0px 5px" class="d-flex flex-row border-top">
            @yield('bottom-nav')
        </div>
    </div>
@endsection
