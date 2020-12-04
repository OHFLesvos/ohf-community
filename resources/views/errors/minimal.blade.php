<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }

            .report-button {
                margin-left: 10px;
                background: #25A6F7;
                color: white;
                padding: 10px 15px;
                font-weight: 500;
                cursor: pointer;
                border: 1px solid #1D87CE;
                box-shadow: 0 1px 1px rgba(0,0,0, .12);
            }

            .error-id {
                margin-left: 1em;
                color: grey;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="code">
                @yield('code')
            </div>
            <div class="message" style="padding: 10px;">
                @yield('message')
                @if(filled(config('sentry.dsn')) && app()->bound('sentry') && app('sentry')->getLastEventId())
                    <small class="error-id">
                        Error ID: {{ app('sentry')->getLastEventId() }}
                    </small>
                @endif
            </div>
        </div>
        @if(filled(config('sentry.dsn')) && app()->bound('sentry') && app('sentry')->getLastEventId())
            <script
                src="https://browser.sentry-cdn.com/5.28.0/bundle.min.js"
                integrity="sha384-1HcgUzJmxPL9dRnZD2wMIj5+xsJfHS+WR+pT2yJNEldbOr9ESTzgHMQOcsb2yyDl"
                crossorigin="anonymous"
            ></script>
            <script>
                Sentry.init({ dsn: '{{ config('sentry.dsn') }}' });
                Sentry.showReportDialog({ eventId: '{{ app('sentry')->getLastEventId() }}' });
            </script>
        @endif
    </body>
</html>
