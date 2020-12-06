@extends('layouts.app')

@section('title', __('coupons.edit_coupon'))

@section('content')

    {!! Form::model($coupon, ['route' => ['coupons.update', $coupon], 'method' => 'put']) !!}

        <div class="form-row">
            <div class="col-md">
                {{ Form::bsText('name', null, [ 'required', 'autofocus' ], __('app.name')) }}
            </div>
            <div class="col-md">
                <label for="icon">@lang('app.icon')</label>
                <div class="input-group mb-3">
                    {{ Form::text('icon', null, [ 'class' => 'form-control', 'id' => 'icon', 'list' => 'icon-list', 'aria-describedby' => 'icon-addon' ]) }}
                    <div class="input-group-append">
                        <span class="input-group-text" id="icon-addon">
                            <x-icon :icon="$coupon->icon"/>
                        </span>
                    </div>
                </div>
                <datalist id="icon-list">
                    @foreach(list_fa_icons() as $icon)
                        <option>{{ $icon }}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md">
                {{ Form::bsNumber('daily_amount', null, [ ], __('people.daily_amount')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('retention_period', null, [ ], __('people.retention_period'), __('app.in_days')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('min_age', null, [ ], __('people.min_age'), __('app.in_years')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('max_age', null, [ ], __('people.max_age'), __('app.in_years')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('daily_spending_limit', null, [ ], __('people.daily_spending_limit'), __('people.per_day')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('newly_registered_block_days', null, [ ], __('people.block_for_newly_registered'), __('app.days')) }}
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md">
                {{ Form::bsNumber('order', null, [ 'min' => 0 ], __('app.order')) }}
            </div>
            <div class="col-md pt-md-4">
                {{ Form::bsCheckbox('enabled', 1, null, __('app.enabled')) }}
                {{ Form::bsCheckbox('returnable', 1, null, __('people.returnable')) }}
                {{ Form::bsCheckbox('qr_code_enabled', 1, null, __('people.with_qr_code')) }}
            </div>
            <div class="col-md">
                {{ Form::bsNumber('code_expiry_days', null, [ ], __('people.code_expiry'), __('app.days')) }}
            </div>
        </div>

        <p>
            <x-form.bs-submit-button :label="__('app.update')"/>
        </p>

    {!! Form::close() !!}

@endsection

@push('footer')
    <script>
        $(function () {
            $('#icon').on('change', function() {
                $('#icon-addon').html('<i class="fa fa-' + $(this).val() + '"></i>');
            });
        });
    </script>
@endpush
