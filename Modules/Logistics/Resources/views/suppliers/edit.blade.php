@extends('layouts.app')

@section('title', __('logistics::suppliers.edit_supplier'))

@section('content')

    {!! Form::model($supplier, ['route' => ['logistics.suppliers.update', $supplier], 'method' => 'put']) !!}

       <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', $supplier->poi->name, [ 'required' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('name_local', $supplier->poi->name_local, [ ], __('app.name_local')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('address', $supplier->poi->address, [ 'required' ], __('app.address')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('address_local', $supplier->poi->address_local, [  ], __('app.address_local')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('latitude', $supplier->poi->latitude, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.latitude')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('longitude', $supplier->poi->longitude, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.longitude')) }}
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
        {{ Form::bsTextarea('description', $supplier->poi->description, [ 'rows' => 3 ], __('app.description')) }}

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}
	
@endsection
