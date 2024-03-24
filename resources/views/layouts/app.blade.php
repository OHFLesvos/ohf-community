<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    @include('layouts.include.head')
    <body class="h-100 d-flex flex-column">
        {{-- Content --}}
        @yield('content')

        {{-- Stack for additional scripts --}}
        @stack('footer')
    </body>
</html>
