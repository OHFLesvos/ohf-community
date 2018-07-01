@extends('layouts.app')

@section('title', __('inventory.take_out_items'))

@section('content')
    {!! Form::open(['route' => ['inventory.transactions.storeEgress', $storage ]]) !!}
        <p>@lang('inventory.take_out_following_items_from_storage', ['storage' => $storage->name]):</p>
        <div class="form-row">
            <div class="col-4 col-sm-3 col-md-2 col-xl-1">
                {{ Form::bsNumber('quantity', $total == 1 ? 1 : null, [ 'autofocus', 'required', 'min' => 1, 'max' => $total], __('inventory.quantity')) }}
            </div>
            <div class="col">
                {{ Form::bsText('item', $item, [ 'readonly' ], __('inventory.item')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('destination', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($destinations)) ], __('inventory.destination')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.remove')) }}
        </p>
    {!! Form::close() !!}
@endsection
