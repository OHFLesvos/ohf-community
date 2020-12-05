<table>
    @foreach($codes as $code)
        @if($loop->index % 2 == 0)
        <tr>
        @endif
        <td class="code-cell">
            <img src="data:image/png;base64,{{ $code }}">
        </td>
        <td class="logo-cell">
            @isset($logo)
                <img class="logo-img" src="{{ $logo }}">
            @endisset
            @isset($label)
                @isset($logo)<br><br>@endisset
                <span class="logo-label">{{ $label }}</span>
            @endif
        </td>
        @if($loop->index % 2 == 1)
        </tr>
        @endif
    @endforeach
</table>

