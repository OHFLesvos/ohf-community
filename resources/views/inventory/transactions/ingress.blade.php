@extends('layouts.app')

@section('title', __('inventory.add_items'))

@section('content')
    {!! Form::open(['route' => ['inventory.transactions.storeIngress', $storage ]]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsText('storage', $storage->name, [ 'disabled' ], __('inventory.storage')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::bsNumber('quantity', null, [ 'autofocus', 'required', 'min' => 1], __('inventory.quantity')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('item', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($items)) ], __('inventory.item')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('origin', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($origins)) ], __('inventory.origin')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.register')) }}
        </p>
    {!! Form::close() !!}
@endsection
