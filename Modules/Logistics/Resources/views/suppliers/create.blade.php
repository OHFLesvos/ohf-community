@extends('layouts.app')

@section('title', __('logistics::suppliers.create_supplier'))

@section('content')

    {!! Form::open(['route' => ['logistics.suppliers.store'], 'method' => 'post']) !!}

       <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('name_local', null, [ ], __('app.name_local')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('address', null, [ 'required' ], __('app.address')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('address_local', null, [  ], __('app.address_local')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('latitude', null, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.latitude')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('longitude', null, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.longitude')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($categories) ], __('app.category')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('phone', null, [ ], __('app.phone')) }}
            </div>
            <div class="col-md">
                {{ Form::bsEmail('email', null, [ ], __('app.email')) }}
            </div>
            <div class="col-md">
                {{ Form::bsUrl('website', null, [ ], __('app.website')) }}
            </div>
        </div>
        {{ Form::bsTextarea('description', null, [ 'rows' => 3 ], __('app.description')) }}

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
