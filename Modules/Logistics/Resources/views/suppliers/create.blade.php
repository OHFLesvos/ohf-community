@extends('layouts.app')

@section('title', __('logistics::suppliers.create_supplier'))

@section('content')

    {!! Form::open(['route' => ['logistics.suppliers.store'], 'method' => 'post']) !!}

       <div class="form-row">
            <div class="col-md">
                <div id="name_input">{{ Form::bsText('name', null, [ 'required', 'autofocus', 'append' => '<button class="btn btn-outline-secondary" type="button">@icon(language)</button>' ], __('app.name')) }}</div>
                <div id="name_input_local">{{ Form::bsText('name_local', null, [ 'append' => '<button class="btn btn-secondary" type="button">@icon(language)</button>' ], __('app.name_local')) }}</div>
            </div>
            <div class="col-md-4">
                {{ Form::bsText('category', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode($categories) ], __('app.category')) }}
            </div>            
        </div>
        <div class="form-row">
            <div class="col-md">
                <div id="street_input">{{ Form::bsText('street', null, [ 'append' => '<button class="btn btn-outline-secondary" type="button">@icon(language)</button>' ], __('app.street')) }}</div>
                <div id="street_input_local">{{ Form::bsText('street_local', null, [ 'append' => '<button class="btn btn-secondary" type="button">@icon(language)</button>' ], __('app.street_local')) }}</div>
            </div>
            <div class="col-md">
                <div id="city_input">{{ Form::bsText('city', null, [ 'append' => '<button class="btn btn-outline-secondary" type="button">@icon(language)</button>' ], __('app.city')) }}</div>
                <div id="city_input_local">{{ Form::bsText('city_local', null, [ 'append' => '<button class="btn btn-secondary" type="button">@icon(language)</button>' ], __('app.city_local')) }}</div>
            </div>
            <div class="col-md-auto">
                {{ Form::bsText('zip', null, [ 'size' => 10 ], __('app.zip')) }}
            </div>
        </div>
        <div class="form-row">            
            <div class="col-md">
                {{ Form::bsText('province', null, [ ], __('app.state_province')) }}
            </div>
            <div class="col-md">
                {{ Form::bsCountryName('country_name', null, [ ], __('app.country')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('latitude', null, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.latitude')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::bsText('longitude', null, [ 'pattern' => '-?\d+\.\d+', 'title' => __('app.decimal_number') ], __('app.longitude')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('google_places_id', null, [ ], __('app.google_places_id')) }}
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
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('tax_number', null, [ ], __('app.tax_number')) }}
            </div>
            <div class="col-md">
                {{ Form::bsTextarea('bank_account', null, [ 'rows' => 2 ], __('app.bank_account')) }}
            </div>
        </div>        
        {{ Form::bsTextarea('description', null, [ 'rows' => 3 ], __('app.description')) }}

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}
	
@endsection

@section('script')
$(function(){

    $('#name_input_local').hide();
    $('#name_input button').on('click', function(){
        $('#name_input').hide();
        $('#name_input_local').show();
        $('#name_input_local input').focus();
    });
    $('#name_input_local button').on('click', function(){
        $('#name_input_local').hide();
        $('#name_input').show();
        $('#name_input input').focus();
    });

    $('#street_input_local').hide();
    $('#street_input button').on('click', function(){
        $('#street_input').hide();
        $('#street_input_local').show();
        $('#street_input_local input').focus();
    });
    $('#street_input_local button').on('click', function(){
        $('#street_input_local').hide();
        $('#street_input').show();
        $('#street_input input').focus();
    });

    $('#city_input_local').hide();
    $('#city_input button').on('click', function(){
        $('#city_input').hide();
        $('#city_input_local').show();
        $('#city_input_local input').focus();
    });
    $('#city_input_local button').on('click', function(){
        $('#city_input_local').hide();
        $('#city_input').show();
        $('#city_input input').focus();
    });
});
@endsection