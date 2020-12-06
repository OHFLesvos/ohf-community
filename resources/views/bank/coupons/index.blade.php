@extends('layouts.app')

@section('title', __('coupons.coupons'))

@section('content')

    @if(count($coupons) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="text-right fit">@lang('people.daily_amount')</th>
                        <th>@lang('people.retention_period')</th>
                        <th>@lang('people.min_age')</th>
                        <th>@lang('people.max_age')</th>
                        <th>@lang('people.daily_spending_limit')</th>
                        <th>@lang('people.block_for_newly_registered')</th>
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
                                <a href="{{ route('coupons.show', $coupon) }}">
                                    {{ $coupon->name }}
                                </a>
                            </td>
                            <td class="text-right fit">{{ $coupon->daily_amount }}</td>
                            <td>{{ $coupon->retention_period != null ? $coupon->retention_period . ' ' . trans_choice('app.day_days', $coupon->retention_period) : __('people.one_time') }}</td>
                            <td>
                                @isset($coupon->min_age)
                                    {{ $coupon->min_age }}
                                    {{ trans_choice('app.year_years', $coupon->min_age) }}
                                @endisset
                            </td>
                            <td>
                                @isset($coupon->max_age)
                                    {{ $coupon->max_age }}
                                    {{ trans_choice('app.year_years', $coupon->max_age) }}
                                @endisset
                            </td>
                            <td>
                                @isset($coupon->daily_spending_limit)
                                    {{ $coupon->daily_spending_limit }}
                                    @lang('people.per_day')
                                @endisset
                            </td>
                            <td>
                                @isset($coupon->newly_registered_block_days)
                                    {{ $coupon->newly_registered_block_days }}
                                    {{ trans_choice('app.day_days', $coupon->newly_registered_block_days) }}
                                @endisset
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
            @lang('coupons.no_coupons_found')
        </x-alert>
    @endif

@endsection
