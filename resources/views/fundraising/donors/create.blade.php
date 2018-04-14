@extends('layouts.app')

@section('title', __('fundraising.create_donor'))

@section('content')

    {!! Form::open(['route' => ['fundraising.donors.store']]) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('first_name', null, [ 'autofocus' ], __('fundraising.first_name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('last_name', null, [ ], __('fundraising.last_name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('company', null, [ ], __('fundraising.company')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('street', null, [ ], __('fundraising.street')) }}
            </div>
            <div class="col-md-2 col-lg-1">
                {{ Form::bsText('zip', null, [  ], __('fundraising.zip')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('city', null, [ ], __('fundraising.city')) }}
            </div>
            <div class="col-md">
                {{ Form::bsCountryName('country_name', null, [ ], __('fundraising.country')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('email', null, [ ], __('app.email')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('phone', null, [ ], __('fundraising.phone')) }}
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
