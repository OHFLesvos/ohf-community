@extends('layouts.app')

@section('title', $supplier->poi->name /*__('logistics::suppliers.view_supplier')*/)

@section('content')

    {{-- Title --}}
    <h2 class="d-none d-sm-block"><span id="supplier_name">{{ $supplier->poi->name }}</span> 
        @isset($supplier->poi->name_local)
            <small><a href="javascript:;" class="replace-content" data-replace-id="supplier_name" data-replace-content="{{ $supplier->poi->name_local }}">@icon(language)</a></small>
        @endisset
    </h2>
    
    {{-- Category --}}
    <h6 class="text-muted">{{ $supplier->category }}</h6>

    <ul class="nav nav-tabs mt-3 mb-3">
        <li class="nav-item">
            <a class="nav-link @if($display == 'info') active @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'info']) }}">@lang('app.info')</a>
        </li>
        <li class="nav-item">
            
            <a class="nav-link @if($display == 'map') active @endif @if($supplier->poi->maps_location == null) disabled @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'map']) }}">@lang('app.map')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($display == 'products') active @endif" href="{{ route('logistics.suppliers.show', [$supplier, 'display' => 'products']) }}">@lang('logistics::products.products')</a>
        </li>
    </ul>

    @if($display == 'info')

        {{-- Address --}}
        <span id="supplier_address">{!! str_replace(", ", "<br>", $supplier->poi->address) !!}</span>
        @isset($supplier->poi->address_local)
            <a href="javascript:;" class="replace-content" data-replace-id="supplier_address" data-replace-content="{!! str_replace(", ", "<br>", $supplier->poi->address_local) !!}">@icon(language)</a>
        @endisset

        @php
            $links = [];
            if (isset($supplier->phone)) {
                $links[] = [
                    'icon' => 'phone',
                    'label' => __('app.phone'),
                    'url' => tel_url($supplier->phone),
                    'text' => $supplier->phone,
                ];
            }
            if (isset($supplier->email)) {
                $links[] = [
                    'icon' => 'envelope',
                    'label' => __('app.email'),
                    'url' => email_url($supplier->email),
                    'text' => $supplier->email,
                ];
            }
            if (isset($supplier->website)) {
                $links[] = [
                    'icon' => 'globe',
                    'label' => __('app.website'),
                    'url' => $supplier->website,
                    'text' => $supplier->website,
                    'attributes' => [ 'target' => '_blank' ],
                ];
            }
        @endphp

        {{-- Links --}}
        @if (count($links) > 0)
            <hr class="d-none d-sm-block">
            <div class="d-none d-sm-table">
                <table class="d-none d-sm-table">
                    <tbody>
                        @foreach($links as $link)
                            <tr>
                                <td class="pr-2">
                                    @icon({{ $link['icon'] }}) {{ $link['label'] }}:
                                </td>
                                <td>
                                    <a href="{{ $link['url'] }}" {!! isset($link['attributes']) ? print_html_attributes($link['attributes']) : '' !!}>
                                        {{ $link['text'] }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-sm-none mt-3 mb-1">
                @foreach($links as $link)
                    <p class="mb-2">
                        <a href="{{ $link['url'] }}" {!! isset($link['attributes']) ? print_html_attributes($link['attributes']) : '' !!} class="btn btn-primary btn-block">
                            @icon({{ $link['icon'] }}) {{ $link['label'] }}
                        </a>
                    </p>
                @endforeach
            </div>
        @endisset
        
        {{-- Description --}}
        @isset($supplier->poi->description)
            <hr>
            <p>{!! nl2br($supplier->poi->description) !!}</p>
        @endisset

    @elseif($display == 'map')
        
        {{-- Map --}}
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

@section('script')
$(function(){
    $('.replace-content').on('click', function(){
        var target_id = $(this).data('replace-id');
        var replacement = $(this).data('replace-content');
        var target = $('#' + target_id);
        $(this).data('replace-content', target.html());
        target.html(replacement);
    });
})
@endsection