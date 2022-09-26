    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="lang" content="{{ str_replace('_', '-', app()->getLocale()) }}">

        {{-- CSRF --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Title --}}
        <title>@if(View::hasSection('site-title'))@yield('site-title') - @elseif(View::hasSection('title'))@yield('title') - @endif{{ config('app.name') }}</title>

        {{-- Favicon --}}
        @isset($favicon_32_url)
            <link rel="icon" href="{{ $favicon_32_url }}" sizes="32x32" />
        @else
            <link rel="icon" href="{{ asset('favicon.ico') }}" />
        @endisset
        @isset($favicon_192_url)
            <link rel="icon" href="{{ $favicon_192_url }}" sizes="192x192" />
        @endisset
        @isset($favicon_180_url)
            <link rel="apple-touch-icon-precomposed" href="{{ $favicon_180_url }}" />
        @endisset

        {{-- CSS --}}
        @vite(['resources/js/app.js','resources/sass/app.scss'])

        {{-- Scripts --}}
        <script>
            window.Laravel = {}
        </script>

        {{-- Stack for additional CSS / scripts --}}
        @stack('head')
    </head>
