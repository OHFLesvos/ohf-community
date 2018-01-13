<html>
    <head>
        <title>Code Card</title>
        <style type="text/css">
            @page {
                margin-top: 0;
                margin-left: 0;
                font-family: sans-serif;
                margin-bottom: 0;
            }
            
            table {
                border-collapse: collapse;
            }

            tr td {
                padding-top: 1.4em;
                padding-bottom: 0.8em;
                border-bottom: 1px solid gray;
            }

            tr td:nth-child(even) {
                border-right: 1px solid gray;
            }

            tr:last-child td {
                padding-bottom: 0;
                border-bottom: none;
            }
            tr td.code-cell {
                padding-left: 1.5em;
            }
            tr td.logo-cell {
                padding-right: 0.5em;
                padding-left: 0.2em;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <table>
            @foreach($codes as $code)
                @if($loop->index % 2 == 0)
                <tr>
                @endif
                <td class="code-cell">
                    <img src="data:image/png;base64,{{ $code }}">
                </td>
                <td class="logo-cell">
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
