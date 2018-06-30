@extends('layouts.app')

@section('title', __('storage.add_items'))

@section('content')
    {!! Form::open(['route' => ['storage.containers.transactions.store', $container ]]) !!}
        <div class="form-row">
            <div class="col-sm-auto">
                {{ Form::bsText('container', $container->name, [ 'disabled' ], __('storage.container')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::bsNumber('quantity', null, [ 'autofocus', 'required', 'min' => 1], __('storage.quantity')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('item', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($items)) ], __('storage.item')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.register')) }}
        </p>
    {!! Form::close() !!}
@endsection
