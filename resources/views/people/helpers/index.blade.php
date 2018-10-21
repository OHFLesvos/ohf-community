@extends('layouts.app')

@section('title', __('people.helpers'))

@section('content')
    <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Scopes">
        @foreach($scopes as $scope)
            <a href="{{ $scope['url'] }}" class="btn @if($scope['active']) btn-dark @else btn-secondary @endif">{{ $scope['label'] }}</a>
        @endforeach
    </div>
    @if( ! $data->isEmpty() )
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
            </table>
        </div>
        <p><small>@lang('app.n_results_found', [ 'num' => $data->count() ])</small></p>
    @else
        @component('components.alert.info')
            @lang('people.no_helpers_found')
        @endcomponent
	@endif

@endsection
