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
            <div class="col-md">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($categories) ], __('app.category')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('street', $supplier->poi->street, [ 'required' ], __('app.street')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('street_local', $supplier->poi->street_local, [  ], __('app.street_local')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('city', $supplier->poi->city, [ ], __('app.city')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('city_local', $supplier->poi->city_local, [ ], __('app.city_local')) }}
            </div>            
            <div class="col-md-auto">
                {{ Form::bsText('zip', $supplier->poi->zip, [ 'size' => 10 ], __('app.zip')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('province', $supplier->poi->province, [ ], __('app.state_province')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('country_name', $supplier->poi->country_name, [ ], __('app.country')) }}
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
