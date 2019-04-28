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

        @if(!$supplier->offers->isEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('app.name')</th>
                            <th class="fit">@lang('app.unit')</th>
                            <th class="fit text-right">@lang('app.price')</th>
                            <th class="fit d-none d-sm-table-cell">@lang('app.last_updated')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->offers as $offer)
                            <tr>
                                <td>
                                    <a href="{{ route('logistics.offers.edit', $offer) }}">{{ $offer->product->name }}</a>
                                    <small class="text-muted">{{ $offer->product->category }}</small>
                                    @isset($offer->remarks)
                                        <br><em>{{ $offer->remarks }}</em>
                                    @endisset
                                </td>
                                <td class="fit">{{ $offer->unit }}</td>
                                <td class="fit text-right">{{ $offer->price }}</td>
                                <td class="fit d-none d-sm-table-cell">{{ (new Carbon\Carbon($offer->updated_at))->toDateString() }}</td>
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

        <p>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOfferModal">
                @icon(plus-circle) @lang('logistics::offers.add_offer')
            </button>
        </p>

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

    @if($errors->get('product_id') != null || $errors->get('price') != null)
        $('#addOfferModal').modal('show');
    @endif    
})
@endsection

@section('content-footer')
    {!! Form::open(['route' => ['logistics.offers.store'], 'method' => 'post']) !!}
        @component('components.modal', [ 'id' => 'addOfferModal' ])
            @slot('title', __('logistics::offers.add_offer'))
            {{ Form::hidden('supplier_id', $supplier->id) }}
            {{ Form::bsAutocomplete('product_id', null, route('logistics.products.filter'), ['placeholder' => __('logistics::products.product')], '') }}
            <div class="form-row">
                <div class="col-sm">
                    {{ Form::bsText('unit', null, [ 'placeholder' => __('app.unit') ], '') }}
                </div>
                <div class="col-sm">
                    {{ Form::bsNumber('price', null, [ 'step' => 'any', 'min' => 0, 'placeholder' => __('app.price')], '', __('app.write_decimal_point_as_comma')) }}
                </div>
            </div>
            {{ Form::bsTextarea('remarks', null, [ 'rows' => 1, 'placeholder' => __('app.remarks') ], '') }}
            @slot('footer')
                <button type="submit" class="btn btn-primary" id="lend-existing-book-button">@icon(check) @lang('logistics::offers.add_offer')</button>                
            @endslot
        @endcomponent
    {!! Form::close() !!}
@endsection
