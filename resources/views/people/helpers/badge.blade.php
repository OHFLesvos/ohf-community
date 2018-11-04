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
                background: lime;
            }

            .page-break {
                page-break-before: always; 
            }

            .frontside {
                background: lightblue;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5.4cm;
            }

            .title {
                background: cyan;
                position: absolute;
                top: 2.2cm;
                width: 100%;
                text-align: center;
            }

            .name {
                font-size: 30pt;
                font-weight: bold;
            }

            .responsibilities {
                font-size: 18pt;
                font-weight: normal;
            }

            .backside {
                background: lightgreen;
                position: absolute;
                top: 7.4cm;
                left: 0;
                right: 0;
                height: 5.4cm;
            }

            .issued {
                background: cyan;
                position: absolute;
                bottom: 0;
                right: 0;
            }
        </style>
    </head>
    <body>
        @foreach($helpers as $helper)
            <div class="frontside @unless($loop->first) page-break  @endunless">
                <div class="title">
                    <div class="name">
                        @isset($helper->person->nickname)
                            {{ $helper->person->nickname }}
                        @else
                            {{ $helper->person->name }}
                        @endisset
                    </div>
                    <div class="responsibilities">
                        @if(is_array($helper->responsibilities) && count($helper->responsibilities) > 0)
                            {{ implode(', ', $helper->responsibilities) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="backside">
                <div style="position: fixed; top: 7.4cm; border-top: 1px dotted gray; left: 0; width: 10.5cm"></div>
                abc
                <div class="issued">
                    Issued: {{ Carbon\Carbon::today()->toDateString() }}
                </div>
            </div>
        @endforeach
    </body>
</html>