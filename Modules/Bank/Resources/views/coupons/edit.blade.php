@extends('layouts.app')

@section('title', __('bank::coupons.edit_coupon'))

@section('content')

    {!! Form::model($coupon, ['route' => ['coupons.update', $coupon], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                <label for="icon">@lang('app.icon')</label>
                <div class="input-group mb-3">
                    {{ Form::text('icon', null, [ 'class' => 'form-control', 'id' => 'icon', 'aria-describedby' => 'icon-addon' ]) }}
                    <div class="input-group-append">
                        <span class="input-group-text" id="icon-addon">
                            @icon({{ $coupon->icon }})
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsNumber('daily_amount', null, [ ], __('people::people.daily_amount')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('retention_period', null, [ ], __('people::people.retention_period'), __('app.in_days')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('min_age', null, [ ], __('people::people.min_age'), __('app.in_years')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('max_age', null, [ ], __('people::people.max_age'), __('app.in_years')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('daily_spending_limit', null, [ ], __('people::people.daily_spending_limit'), __('people::people.per_day')) }}
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md">
                {{ Form::bsNumber('order', null, [ 'min' => 0 ], __('app.order')) }}
            </div>
            <div class="col-md pt-md-4">
                {{ Form::bsCheckbox('enabled', 1, null, __('app.enabled')) }}
                {{ Form::bsCheckbox('returnable', 1, null, __('people::people.returnable')) }}
                {{ Form::bsCheckbox('qr_code_enabled', 1, null, __('people::people.with_qr_code')) }}
                {{ Form::bsCheckbox('allow_for_helpers', 1, null, __('people::people.allow_for_helpers')) }}
            </div>
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
$(function(){
    $('#icon').autocomplete({
        lookup: {!! json_encode(list_fa_icons()) !!},
        onSelect: function(suggestion){
            $('#icon-addon').html('<i class="fa fa-' + suggestion.value + '"></i>');
        }
    });
});
@endsection
