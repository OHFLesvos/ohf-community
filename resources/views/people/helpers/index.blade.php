@extends('layouts.app')

@section('title', __('people.helpers'))

@section('content')
    <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Scopes">
        @foreach($scopes as $scope)
            <a href="{{ $scope['url'] }}" class="btn @if($scope['active']) btn-dark @else btn-secondary @endif">{{ $scope['label'] }}</a>
        @endforeach
    </div>
    @isset($groups)
        @component('people.helpers.table', ['fields' => $fields])
            @foreach($groups as $group)
                @if( $data[$loop->index]->count() > 0)
                    <tr class="table-secondary"><th colspan="{{ $fields->count() }}">{{ $group }} <small>({{ $data[$loop->index]->count() }})</small></th></tr>
                    @include('people.helpers.tablebody', ['data' => $data[$loop->index]])
                @endif
            @endforeach
        @endcomponent
    @else
        @if( ! $data->isEmpty() )
            @component('people.helpers.table', ['fields' => $fields])
                @include('people.helpers.tablebody')
            @endcomponent
            <p><small>@lang('app.n_results_found', [ 'num' => $data->count() ])</small></p>
        @else
            @component('components.alert.info')
                @lang('people.no_helpers_found')
            @endcomponent
        @endif
    @endisset

@endsection
