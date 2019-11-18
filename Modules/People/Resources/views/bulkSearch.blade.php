@extends('layouts.app')

@section('title', __('app.bulk_search'))

@section('content')

    {!! Form::open(['route' => 'people.doBulkSearch']) !!}
        {{ Form::bsTextarea('data', null, ['autofocus'], __('app.data')) }}
        {{ Form::bsSelect('order', $orders, $order, [], __('app.order')) }}
        <p>
            {{ Form::bsSubmitButton(__('app.search'), 'search') }}
        </p>
    {!! Form::close() !!}
    
@endsection

