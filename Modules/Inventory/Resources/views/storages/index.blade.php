@extends('layouts.app')

@section('title', __('inventory::inventory.inventory_management'))

@section('content')

    @if( ! $storages->isEmpty() )
        <div class="list-group">
            @foreach($storages as $storage)
                <a href="{{ route('inventory.storages.show', $storage) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $storage->name }}</h5>
                    @php
                        $num_items = $storage->transactions()
                            ->groupBy('item')
                            ->select(DB::raw('sum(quantity) as total'), 'item')
                            ->having('total', '>=', 1)
                            ->get()
                            ->count();
                    @endphp
                    <small>{{ trans_choice('inventory::inventory.num_items', $num_items, ['num' => $num_items]) }}</small>
                    </div>
                    {{-- <p class="mb-1">Text</p> --}}
                    <small>{{ $storage->description }}</small>
                </a>
            @endforeach
        </div>
    @else
        @component('components.alert.info')
            @lang('inventory::inventory.no_storages_defined')
        @endcomponent
	@endif
	
@endsection
