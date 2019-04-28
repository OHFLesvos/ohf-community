@extends('layouts.app')

@section('title', __('logistics::offers.edit_offer'))

@section('content')

    {!! Form::model($offer, ['route' => ['logistics.offers.update', $offer], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('supplier', $offer->supplier->poi->name, [ 'readonly' ], __('logistics::suppliers.supplier')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('product', $offer->product->name, [ 'readonly' ], __('logistics::products.product')) }}
            </div>
        </div>

        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('unit', null, [ ], __('app.unit')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsNumber('price', null, [ 'step' => 'any', 'min' => 0 ], __('app.price'), __('app.write_decimal_point_as_comma')) }}
            </div>
        </div>
        {{ Form::bsTextarea('remarks', null, [ 'rows' => 1 ], __('app.remarks')) }}

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
