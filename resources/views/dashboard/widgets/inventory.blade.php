@php
    $links = [

    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('inventory.inventory_management'))

@section('widget-content')
    <div class="card-body">
        @foreach($storages as $storage)
            <a href="{{ route('inventory.storages.show', $storage) }}">
                {{ $storage->name }}
            </a><br>
        @endforeach                    
    </div>
@endsection
