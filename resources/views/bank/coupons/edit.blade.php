@extends('layouts.app')

@section('title', __('people.edit_coupon'))

@section('content')

    {!! Form::model($coupon, ['route' => ['coupons.update', $coupon], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                {{ Form::bsText('icon', null, [ ], __('app.icon')) }}
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsNumber('daily_amount', null, [ ], __('people.daily_amount')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('retention_period', null, [ ], __('people.retention_period')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('min_age', null, [ ], __('people.min_age')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('max_age', null, [ ], __('people.max_age')) }}
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md">
                {{ Form::bsNumber('order', null, [ ], __('app.order')) }}
            </div>
            <div class="col-md pt-md-4">
                {{ Form::bsCheckbox('enabled', 1, null, __('app.enabled')) }}
                {{ Form::bsCheckbox('returnable', 1, null, __('people.returnable')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
