@php
    $links = [

    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('inventory.inventory_management'))

@section('widget-content')
    <div class="card-body">
        @forelse($storages as $storage)
            <a href="{{ route('inventory.storages.show', $storage) }}">
                {{ $storage->name }}
            </a><br>
        @empty
            <em>@lang('inventory.no_storages_defined')</em>
        @endforelse                 
    </div>
@endsection
