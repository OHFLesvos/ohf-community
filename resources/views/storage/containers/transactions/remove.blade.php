@extends('layouts.app')

@section('title', __('storage.remove_items'))

@section('content')
    {!! Form::open(['route' => ['storage.containers.transactions.storeRemove', $container ]]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsText('container', $container->name, [ 'disabled' ], __('storage.container')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::bsNumber('quantity', $total == 1 ? 1 : null, [ 'autofocus', 'required', 'min' => 1, 'max' => $total], __('storage.quantity')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('item', $item, [ 'readonly' ], __('storage.item')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.remove')) }}
        </p>
    {!! Form::close() !!}
@endsection
