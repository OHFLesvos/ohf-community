@extends('layouts.app')

@section('title', __('fundraising.create_donor'))

@section('content')

    {!! Form::open(['route' => ['fundraising.donors.store']]) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('salutation', null, [ 'autofocus', 'list' => $salutations ], __('app.salutation')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('first_name', null, [ ], __('app.first_name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('last_name', null, [ ], __('app.last_name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('company', null, [ ], __('app.company')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('street', null, [ ], __('app.street')) }}
            </div>
            <div class="col-md-2 col-lg-1">
                {{ Form::bsText('zip', null, [  ], __('app.zip')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('city', null, [ ], __('app.city')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('country_name', null, [ 'list' => $countries ], __('app.country')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsEmail('email', null, [ ], __('app.email')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('phone', null, [ ], __('app.phone')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('language', null, [ 'list' => $languages ], __('app.correspondence_language')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsTextarea('remarks', null, [ 'rows' => 2 ], __('app.remarks')) }}
            </div>
            <div class="col-md">
                {{ Form::bsTags('tags', null, [ 'data-suggestions' => json_encode($tag_suggestions) ], __('app.tags')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.create')) }}
        </p>

    {!! Form::close() !!}

@endsection
