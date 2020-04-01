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

        <link rel="icon" href="{{ asset('/img/favicon-32x32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('/img/favicon-192x192.png') }}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{ asset('/img/favicon-180x180.png') }}" />

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
