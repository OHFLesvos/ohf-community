        <tbody>
            @foreach($data as $id => $fields)
                <tr>
                    @foreach($fields as $field)
                        <td>
                            {{-- TODO @can('view', $helper) --}}
                            @if(isset($field['detail_link']) && $field['detail_link'])<a href="{{ route('people.helpers.show', $id) }}">@endif
                            {!! $field['value'] !!}
                            @if(isset($field['detail_link']) && $field['detail_link'])</a>@endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
