@extends('layouts.app')

@section('title', __('donations.create_donor'))

@section('content')

    {!! Form::open(['route' => ['donors.store']]) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('address', null, [ ], __('donations.address')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-2">
                {{ Form::bsText('zip', null, [ ], __('donations.zip')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('city', null, [ ], __('donations.city')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('country', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($countries)) ], __('donations.country')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('email', null, [ ], __('app.email')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('phone', null, [ ], __('donations.phone')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsTextarea('remarks', null, [ 'rows' => 2 ], __('app.remarks')) }}
            </div>
        </div>
        
        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection
