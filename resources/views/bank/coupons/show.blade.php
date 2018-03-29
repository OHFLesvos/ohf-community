@extends('layouts.app')

@section('title', __('people.view_coupon'))

@section('content')

    <ul class="list-group list-group-flush mb-2">
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.name')</strong></div>
                <div class="col-sm">@icon({{ $coupon->icon }}) {{ $coupon->name }}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('people.daily_amount')</strong></div>
                <div class="col-sm">{{ $coupon->daily_amount }}</div>
            </div>
        </li>
        @isset($coupon->retention_period)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm"><strong>@lang('people.retention_period')</strong></div>
                    <div class="col-sm">{{ $coupon->retention_period }} {{ trans_choice('app.day_days', $coupon->retention_period) }}</div>
                </div>
            </li>
        @endisset
        @isset($coupon->min_age)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm"><strong>@lang('people.min_age')</strong></div>
                    <div class="col-sm">{{ $coupon->min_age }} {{ trans_choice('app.year_years', $coupon->min_age) }}</div>
                </div>
            </li>
        @endisset
        @isset($coupon->max_age)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm"><strong>@lang('people.max_age')</strong></div>
                    <div class="col-sm">{{ $coupon->max_age }} {{ trans_choice('app.year_years', $coupon->max_age) }}</div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.order')</strong></div>
                <div class="col-sm">{{ $coupon->order }}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.enabled')</strong></div>
                <div class="col-sm">@if($coupon->enabled) @icon(check) @else @icon(times) @endif</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('people.returnable')</strong></div>
                <div class="col-sm">@if($coupon->returnable) @icon(check) @else @icon(times) @endif</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.registered')</strong></div>
                <div class="col-sm">{{ $coupon->created_at }}</div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm"><strong>@lang('app.last_updated')</strong></div>
                <div class="col-sm">{{ $coupon->updated_at }}</div>
            </div>
        </li>
    </ul>

@endsection
