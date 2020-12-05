@extends('layouts.app')

@section('title', __('app.bulk_search'))

@section('content')

    {!! Form::open(['route' => 'people.doBulkSearch']) !!}
        {{ Form::bsTextarea('data', null, [ 'autofocus', 'required' ], __('app.data')) }}
        {{ Form::bsSelect('order', $orders, $order, [ 'required' ], __('app.order')) }}
        <p>
            <x-form.bs-submit-button :label="__('app.search')" icon="search"/>
        </p>
    {!! Form::close() !!}

@endsection

