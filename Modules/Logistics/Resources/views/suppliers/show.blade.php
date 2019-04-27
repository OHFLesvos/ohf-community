@extends('layouts.app')

@section('title', __('logistics::suppliers.view_supplier'))

@section('content')

    <h2>{{ $supplier->poi->name }}</h2>
    <h6 class="text-muted">{{ $supplier->category }}</h6>

    <ul class="nav nav-tabs mt-3 mb-3">
        <li class="nav-item">
            <a class="nav-link @if($display == 'info') active @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'info']) }}">@lang('app.info')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($display == 'map') active @endif @if($supplier->poi->latitude == null || $supplier->poi->longitude == null) disabled @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'map']) }}">@lang('app.map')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($display == 'products') active @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'products']) }}">@lang('logistics::products.products')</a>
        </li>
    </ul>

    @if($display == 'info')

        @isset($supplier->poi->description)
            <p>{{ $supplier->poi->description }}</p>
        @endisset

        <p>
            @icon(map-marker) {!! gmaps_link(str_replace(", ", "<br>", $supplier->poi->address), $supplier->poi->maps_location) !!}
            @isset($supplier->phone)
                <br>@icon(phone) {!! tel_link($supplier->phone) !!}<br>
            @endisset
            @isset($supplier->email)
                @icon(envelope) {!! email_link($supplier->email) !!}<br>
            @endisset
            @isset($supplier->website)
                @icon(globe) <a href="{{ $supplier->website }}" target="_blank">{{ $supplier->website }}</a><br>
            @endisset
        </p>

    @elseif($display == 'map')
        
        {!! gmaps_embedd($supplier->poi->maps_location) !!}

    @elseif($display == 'products')

        @if(!$supplier->products->isEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.name')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @component('components.alert.info')
                @lang('logistics::products.no_products_found')
            @endcomponent
        @endif

    @endif

@endsection
