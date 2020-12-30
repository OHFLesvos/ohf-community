@extends('layouts.app')

@section('title', __('bank.coupons'))

@section('content')
    @if(count($coupons) > 0)
        <div class="table-responsive">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="text-right fit">@lang('people.daily_amount')</th>
                        <th>@lang('people.retention_period')</th>
                        <th>@lang('app.conditions')</th>
                        <th class="text-right fit">@lang('app.order')</th>
                        <th class="text-center fit">@lang('app.enabled')</th>
                        <th class="text-center fit">@lang('people.returnable')</th>
                        <th class="text-center fit">@lang('people.qr_code')</th>
                        <th>@lang('people.code_expiry')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td>
                                <x-icon :icon="$coupon->icon"/>
                                @can('update', $coupon)
                                    <a href="{{ route('bank.coupons.edit', $coupon) }}">
                                        {{ $coupon->name }}
                                    </a>
                                @else
                                    {{ $coupon->name }}
                                @endcan
                            </td>
                            <td class="text-right fit">{{ $coupon->daily_amount }}</td>
                            <td>{{ $coupon->retention_period != null ? $coupon->retention_period . ' ' . trans_choice('app.day_days', $coupon->retention_period) : __('people.one_time') }}</td>
                            <td>
                                <small>
                                    @isset($coupon->min_age)
                                        <strong>@lang('people.min_age'):</strong>
                                        {{ $coupon->min_age }}
                                        {{ trans_choice('app.year_years', $coupon->min_age) }}
                                        <br>
                                    @endisset
                                    @isset($coupon->max_age)
                                        <strong>@lang('people.max_age'):</strong>
                                        {{ $coupon->max_age }}
                                        {{ trans_choice('app.year_years', $coupon->max_age) }}
                                        <br>
                                    @endisset
                                    @isset($coupon->daily_spending_limit)
                                        <strong>@lang('people.daily_spending_limit'):</strong>
                                        {{ $coupon->daily_spending_limit }}
                                        @lang('people.per_day')
                                        <br>
                                    @endisset
                                    @isset($coupon->newly_registered_block_days)
                                        <strong>@lang('people.block_for_newly_registered'):</strong>
                                        {{ $coupon->newly_registered_block_days }}
                                        {{ trans_choice('app.day_days', $coupon->newly_registered_block_days) }}
                                        <br>
                                    @endisset`
                                </small>
                            </td>
                            <td class="text-right fit">{{ $coupon->order }}</td>
                            <td class="text-center fit"><x-icon-status :check="$coupon->enabled"/></td>
                            <td class="text-center fit"><x-icon-status :check="$coupon->returnable"/></td>
                            <td class="text-center fit"><x-icon-status :check="$coupon->qr_code_enabled"/></td>
                            <td>
                                @if($coupon->qr_code_enabled && isset($coupon->code_expiry_days))
                                    {{ $coupon->code_expiry_days }}
                                    {{ trans_choice('app.day_days', $coupon->code_expiry_days) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-alert type="info">
            @lang('bank.no_coupons_found')
        </x-alert>
    @endif
@endsection
