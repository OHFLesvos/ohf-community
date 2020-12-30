<table class="table mb-0">
    <tbody>
        @foreach($items as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                @if(is_numeric($value))
                    <td class="text-right">
                        {{ number_format($value) }}
                    </td>
                @else
                    <td>{{ $value }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
