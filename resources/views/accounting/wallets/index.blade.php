@extends('layouts.app')

@section('title', __('accounting.wallets'))

@section('content')

    @if(! $wallets->isEmpty())
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>@lang('app.name')</th>
                    <th class="text-right">@lang('app.amount')</th>
                    <th class="text-right">@lang('accounting.transactions')</th>
                    <th class="fit text-center">@lang('app.default')</th>
                    <th class="fit text-center">@lang('app.restricted')</th>
                    <th class="text-right">@lang('app.latest_activity')</th>
                    <th class="fit">@lang('app.created')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wallets as $wallet)
                    <tr>
                        <td>
                            <a href="{{ route('accounting.wallets.edit', $wallet) }}">{{ $wallet->name }}</a>
                        </td>
                        <td class="text-right">
                            {{ number_format($wallet->amount, 2) }}
                        </td>
                        <td class="text-right">
                            {{ $wallet->transactions()->count() }}
                        </td>
                        <td class="fit text-center">
                            @if($wallet->is_default)@icon(check)@else @icon(times) @endif
                        </td>
                        <td class="fit text-center">
                            @if($wallet->roles()->exists())@icon(check)@else @icon(times) @endif
                        </td>
                        <td class="fit">
                            {{ optional($wallet->latestActivity)->toDateTimeString() }}
                        </td>
                        <td class="fit">
                            {{ $wallet->created_at->toDateString() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @component('components.alert.info')
            @lang('accounting.no_wallets_found')
        @endcomponent
    @endif

@endsection
