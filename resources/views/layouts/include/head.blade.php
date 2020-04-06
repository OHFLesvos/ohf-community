    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="lang" content="{{ App::getLocale() }}">

        <title>@if(View::hasSection('title')) @yield('title') - @endif{{ config('app.name') }} - {{ config('app.product_name') }}</title>

        <link href="{{ asset('css/styles.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/app.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">

        @yield('head-meta')

        @isset($favicon_32_url)
            <link rel="icon" href="{{ $favicon_32_url }}" sizes="32x32" />
        @endisset
        @isset($favicon_192_url)
            <link rel="icon" href="{{ $favicon_192_url }}" sizes="192x192" />
        @endisset
        @isset($favicon_180_url)
            <link rel="apple-touch-icon-precomposed" href="{{ $favicon_180_url }}" />
        @endisset

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
