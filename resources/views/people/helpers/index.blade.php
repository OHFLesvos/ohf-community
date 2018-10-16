@extends('layouts.app')

@section('title', __('people.helpers'))

@section('content')

    @if( ! $helpers->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        @foreach($fields as $field)
                            <th>
                                @isset($field['icon'])<span class="d-none d-sm-inline">@icon({{$field['icon']}}) </span>@endisset
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
                                    @if(isset($field['detail_link']) && $field['detail_link'])<a href="{{ route('people.helpers.show', $helper) }}">@endif
                                    @isset($field['value_html'])
                                        {{ $field['prefix'] ?? '' }}{!! $field['value_html']($helper) !!}
                                    @else
                                        @if(gettype($field['value']) == 'string')
                                            {{ $field['prefix'] ?? '' }}{{ $helper->{$field['value']} }}
                                        @else
                                            {{ $field['prefix'] ?? '' }}{{ $field['value']($helper) }}
                                        @endif
                                    @endisset
                                    @if(isset($field['detail_link']) && $field['detail_link'])</a>@endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('people.no_helpers_found')
        @endcomponent
	@endif

@endsection
