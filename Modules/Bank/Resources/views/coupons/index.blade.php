@extends('layouts.app')

@section('title', __('bank::coupons.coupons'))

@section('content')

    @if(count($coupons) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th>@lang('people.daily_amount')</th>
                        <th>@lang('people.retention_period')</th>
                        <th>@lang('people.min_age')</th>
                        <th>@lang('people.max_age')</th>
                        <th>@lang('people.daily_spending_limit')</th>
                        <th>@lang('people.block_for_newly_registered')</th>
                        <th>@lang('app.order')</th>
                        <th>@lang('app.enabled')</th>
                        <th>@lang('people.returnable')</th>
                        <th>@lang('people.qr_code')</th>
                        <th>@lang('people.code_expiry')</th>
                        <th>@lang('helpers::helpers.helpers')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td>
                                @icon({{ $coupon->icon }})
                                <a href="{{ route('coupons.show', $coupon) }}">{{ $coupon->name }}</a>
                            </td>
                            <td>{{ $coupon->daily_amount }}</td>
                            <td>{{ $coupon->retention_period != null ? $coupon->retention_period . ' ' . trans_choice('app.day_days', $coupon->retention_period) : __('people.one_time') }}</td>
                            <td>@isset($coupon->min_age) {{ $coupon->min_age }} {{ trans_choice('app.year_years', $coupon->min_age) }} @endisset</td>
                            <td>@isset($coupon->max_age) {{ $coupon->max_age }} {{ trans_choice('app.year_years', $coupon->max_age) }} @endisset</td>
                            <td>@isset($coupon->daily_spending_limit) {{ $coupon->daily_spending_limit }} @lang('people.per_day') @endisset</td>
                            <td>@isset($coupon->newly_registered_block_days) {{ $coupon->newly_registered_block_days }} {{ trans_choice('app.day_days', $coupon->newly_registered_block_days) }} @endisset</td>
                            <td>{{ $coupon->order }}</td>
                            <td>@if($coupon->enabled) @icon(check) @else @icon(times) @endif</td>
                            <td>@if($coupon->returnable) @icon(check) @else @icon(times) @endif</td>
                            <td>@if($coupon->qr_code_enabled) @icon(check) @else @icon(times) @endif</td>
                            <td>@if($coupon->qr_code_enabled)@isset($coupon->code_expiry_days) {{ $coupon->code_expiry_days }} {{ trans_choice('app.day_days', $coupon->code_expiry_days) }} @endisset @endif</td>
                            <td>@if($coupon->allow_for_helpers) @icon(check) @else @icon(times) @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('bank::coupons.no_coupons_found')
        @endcomponent
	@endif
	
@endsection
