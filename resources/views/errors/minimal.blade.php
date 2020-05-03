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
        </style>

        {{-- Sentry SDK --}}
        @if(config('sentry.dsn') != '')
            <script src="https://browser.sentry-cdn.com/5.15.5/bundle.min.js" integrity="sha384-wF7Jc4ZlWVxe/L8Ji3hOIBeTgo/HwFuaeEfjGmS3EXAG7Y+7Kjjr91gJpJtr+PAT" crossorigin="anonymous"></script>
        @endif
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="code">
                @yield('code')
            </div>
            <div class="message" style="padding: 10px;">
                @yield('message')
            </div>
            @if(config('sentry.dsn') != '' && Sentry\State\Hub::getCurrent()->getLastEventId())
                <button class="report-button">Report this issue</button>
            @endif
        </div>
        {{-- Sentry user feedback report form --}}
        @if(config('sentry.dsn') != '' && Sentry\State\Hub::getCurrent()->getLastEventId())
            <script>
                window.onload = function(e) {
                    document.querySelector(".report-button").addEventListener('click', function() {
                        Sentry.init({ dsn: '{{ config('sentry.dsn') }}' });
                        Sentry.showReportDialog({ eventId: '{{ Sentry\State\Hub::getCurrent()->getLastEventId() }}' })
                    })
                }
            </script>
        @endif
    </body>
</html>
