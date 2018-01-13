<html>
    <head>
        <title>Code Card</title>
        <style type="text/css">
            @page {
                margin-top: 0;
                margin-left: 1.5em;
                font-family: sans-serif;
                margin-bottom: 0;
            }
            table {
                border-collapse: collapse;
            }
            tr td {
                padding-top: 1.4em;
                padding-bottom: 0.8em;
                border-bottom: 1px solid black;
            }
            tr:last-child td {
                padding-bottom: 0;
            }
        </style>
    </head>
    <body>
        <table>
            @foreach($codes as $code)
                @if($loop->index % 2 == 0)
                <tr>
                @endif
                <td>
                    <img src="data:image/png;base64,{{ $code }}">
                </td>
                <td style="padding-right: 2em; padding-left: 0.2em;text-align:center;">
                    <img src="data:image/png;base64,{{ $logo }}" style="width: 300px">
                    <br><span style="font-size: 0.6em; color: #333;">www.ohf-lesvos.org</span>
                </td>
                @if($loop->index % 2 == 1)
                </tr>
                @endif
            @endforeach
        </table>
    </body>
</html>
