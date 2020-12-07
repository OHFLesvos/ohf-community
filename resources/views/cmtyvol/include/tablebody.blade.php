        <tbody>
            @foreach($data as $item)
                <tr>
                    @foreach($item['fields'] as $field)
                        <td>
                            @can('view', $item['model'])
                                @if(isset($field['detail_link']) && $field['detail_link'])<a href="{{ route('cmtyvol.show', $item['model']) }}">@endif
                            @endcan
                            {!! $field['value'] !!}
                            @can('view', $item['model'])
                                @if(isset($field['detail_link']) && $field['detail_link'])</a>@endif
                            @endcan
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
