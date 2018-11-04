<html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 1cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                {{-- background: lime; --}}
            }

            .page-break {
                page-break-before: always; 
            }

            .frontside {
                {{-- background: lightblue; --}}
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5.4cm;
            }

            .logo {
                position: absolute;
                top: 0cm;
                width: 100%;
                text-align: center;
            }

            .logo > img {
                height: 1.5cm;
            }

            .title {
                {{-- background: cyan; --}}
                position: absolute;
                top: 1.9cm;
                width: 100%;
                text-align: center;
            }

            .name {
                font-size: 36pt;
                font-weight: bold;
            }

            .responsibilities {
                font-size: 14pt;
                font-weight: normal;
            }

            .backside {
                {{-- background: lightgreen; --}}
                position: absolute;
                top: 7.4cm;
                left: 0;
                right: 0;
                height: 5.4cm;
            }

            .issued {
                {{-- background: cyan; --}}
                position: absolute;
                top: 5.0cm;
                right: 0cm;
                font-size: 8pt;
            }

            .rotate {
                /* Safari */
                -webkit-transform: rotate(-180deg);

                /* Firefox */
                -moz-transform: rotate(-180deg);

                /* IE */
                -ms-transform: rotate(-180deg);

                /* Opera */
                -o-transform: rotate(-180deg);

                /* Internet Explorer */
                filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            }

            .borders {
                position: fixed; 
                top: 0;
                height: 14.78cm; 
                border: 1px dotted gray;
                left: 0; 
                width: 10.48cm;
            }

            .mid-border {
                position: fixed; 
                top: 7.4cm;
                border-top: 1px dotted gray;
                left: 0; 
                width: 10.48cm;
            }
        </style>
    </head>
    <body>
        @foreach($helpers as $helper)
            <div class="frontside @unless($loop->first) page-break  @endunless">
                @include('people.helpers.badge-content')
                <div class="borders"></div>
                <div class="mid-border"></div>
            </div>
            <div class="backside rotate">
                @include('people.helpers.badge-content')
            </div>
        @endforeach
    </body>
</html>