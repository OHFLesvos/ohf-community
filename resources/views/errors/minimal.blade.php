<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | {{ config('app.name') }}</title>

        @vite(['resources/js/app.js'])
    </head>
    <body class="bg-light h-100 d-flex">
        <main class="container align-self-center">
            <div class="d-flex w-auto align-items-center justify-content-center">
                <div class="display-1 pr-3 border-right border-dark">
                    <strong>@yield('code')</strong>
                </div>
                <div class="pl-3 display-4">
                    @yield('message')
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="/" class="btn btn-outline-dark">Back to start page</a><br>
            </div>
            <p class="text-center my-5">
                {{ config('app.name') }} | @include('layouts.include.copyright')
            </p>
        </main>
    </body>
</html>
