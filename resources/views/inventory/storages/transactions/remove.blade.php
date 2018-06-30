@extends('layouts.app')

@section('title', __('inventory.remove_items'))

@section('content')
    {!! Form::open(['route' => ['inventory.storages.transactions.storeRemove', $storage ]]) !!}
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
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.remove')) }}
        </p>
    {!! Form::close() !!}
@endsection
