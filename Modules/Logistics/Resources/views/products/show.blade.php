@extends('layouts.app')

@section('title', __('logistics::products.view_product'))

@section('content')

    <h1>{{ $product->name }}</h1>
    <p>{{ $product->category }}</p>

    {{-- Remarks --}}
    @isset($product->remarks)
        <hr>
        <p><em>{!! nl2br(e($product->remarks)) !!}</em></p>
    @endisset

    {{-- Suppliers --}}
    @if(!$product->suppliers->isEmpty())
        <div class="card mb-3">
            <div class="card-header">@lang('logistics::suppliers.suppliers')</div>
            <div class="list-group list-group-flush">
                @foreach($product->suppliers->sortBy('name') as $supplier)
                    <a class="list-group-item" href="{{ route('logistics.suppliers.show', $supplier) }}">
                        {{ $supplier->name }}
                        <small class="text-muted pull-right">{{ $supplier->category }}</small>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        @component('components.alert.info')
            @lang('logistics::suppliers.no_suppliers_registered')
        @endcomponent
    @endif

@endsection