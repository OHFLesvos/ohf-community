@extends('layouts.app')

@section('title', __('logistics::logistics.logistics'))

@section('content')
    <div class="row">
        @can('list', \Modules\Logistics\Entities\Supplier::class)
            <div class="col">
                <a href="{{ route('logistics.suppliers.index') }}" class="btn btn-primary btn-lg btn-block">@icon(location-arrow) @lang('logistics::suppliers.suppliers')</a>
            </div>
        @endcan
        @can('list', \Modules\Logistics\Entities\Product::class)
            <div class="col">
                <a href="{{ route('logistics.products.index') }}" class="btn btn-primary btn-lg btn-block">@icon(shopping-basket) @lang('logistics::products.products')</a>
            </div>
        @endcan
    </div>
    
@stop
