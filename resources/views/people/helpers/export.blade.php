<table>
    <thead>
        <tr>
            @foreach($fields as $field)
                <th>
                    {{ $field['label'] }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($helpers as $helper)
            <tr>
                @foreach($fields as $field)
                    <td>
                        @if(gettype($field['value']) == 'string')
                            {{ $field['prefix'] ?? '' }}{{ $helper->{$field['value']} }}
                        @else
                            {{ $field['prefix'] ?? '' }}{{ $field['value']($helper) }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>