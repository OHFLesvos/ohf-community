@extends('layouts.app', ['content_padding' => 'p-0'])

@section('content')
    @php
        $currentRouteName = Route::currentRouteName();
    @endphp
    <div class="d-flex flex-column h-100" style="margin: 0; padding: 0">
        <div style="flex: 0 0 auto; box-shadow: lightgrey 0px 0px 5px" class="d-flex flex-row border-bottom">
            @foreach($nav_elements as $element)
                @if($element['authorized'])
                    <a href="{{ $element['url'] }}" class="text-center p-3  @if($element['active']($currentRouteName)) tab-active @else tab-inactive @endif col">
                        <x-icon :icon="$element['icon']"/>
                        {{ $element['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
        <div style="flex: 1; overflow-y: auto" class="py-4 px-3">
            @yield('wrapped-content')
        </div>
    </div>
@endsection
