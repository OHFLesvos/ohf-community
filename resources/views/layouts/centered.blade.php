<!doctype html>
<html lang="{{ config('app.locale') }}" class="h-100">
    @include('layouts.include.head')
    <body class="h-100 d-flex bg-light">
        <div class="my-auto mx-auto p-3" style="width: {{ $width ?? 450 }}px">
            @yield('centered-content')
        </div>
    </body>
</html>
