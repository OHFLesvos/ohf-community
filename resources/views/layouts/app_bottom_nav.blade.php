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
            @php
                $currentRouteName = Route::currentRouteName();
            @endphp
            @foreach($bottom_nav_elements as $element)
                @if($element['authorized'])
                    <a href="{{ $element['url'] }}" class="btn text-center p-2 @if($element['active']($currentRouteName)) btn-white text-primary @else btn-white text-muted @endif col" title="@isset($element['description']){{ $element['description'] }}@endisset">
                        @icon({{ $element['icon'] }})<br>{{ $element['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endsection
