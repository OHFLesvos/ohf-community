@extends('layouts.app')

@section('title', __('storage.storage_management'))

@section('content')

    @if( ! $containers->isEmpty() )
        <div class="list-group">
            @foreach($containers as $container)
                <a href="{{ route('storage.containers.show', $container) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $container->name }}</h5>
                    @php
                        $num_items = $container->transactions()->groupBy('item')->get()->count();
                    @endphp
                    <small>{{ trans_choice('storage.num_items', $num_items, ['num' => $num_items]) }}</small>
                    </div>
                    {{-- <p class="mb-1">{{ $container->description }}</p> --}}
                    <small>{{ $container->description }}</small>
                </a>
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            @lang('storage.no_containers_defined')
        @endcomponent
	@endif
	
@endsection
