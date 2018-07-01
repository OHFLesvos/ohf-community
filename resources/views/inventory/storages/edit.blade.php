@extends('layouts.app')

@section('title', __('inventory.edit_storage'))

@section('content')
    {!! Form::model($storage, ['route' => ['inventory.storages.update', $storage ], 'method' => 'put']) !!}
        {{ Form::bsText('name', null, [ 'autofocus', 'required' ], __('app.name')) }}
        {{ Form::bsTextarea('description', null, [ ], __('app.description')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>
    {!! Form::close() !!}
@endsection
