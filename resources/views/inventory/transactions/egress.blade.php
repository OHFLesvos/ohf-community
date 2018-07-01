@extends('layouts.app')

@section('title', __('inventory.take_out_items'))

@section('content')
    {!! Form::open(['route' => ['inventory.transactions.storeEgress', $storage ]]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsText('storage', $storage->name, [ 'disabled' ], __('inventory.storage')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::bsNumber('quantity', $total == 1 ? 1 : null, [ 'autofocus', 'required', 'min' => 1, 'max' => $total], __('inventory.quantity')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('item', $item, [ 'readonly' ], __('inventory.item')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('destination', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($destinations)) ], __('inventory.destination')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.remove')) }}
        </p>
    {!! Form::close() !!}
@endsection
