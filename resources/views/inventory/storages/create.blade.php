@extends('layouts.app')

@section('title', __('inventory.add_storage'))

@section('content')
    {!! Form::open(['route' => ['inventory.storages.store' ]]) !!}
        {{ Form::bsText('name', null, [ 'autofocus', 'required' ], __('app.name')) }}
        {{ Form::bsTextarea('description', null, [ ], __('app.description')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.add')) }}
        </p>
    {!! Form::close() !!}
@endsection
