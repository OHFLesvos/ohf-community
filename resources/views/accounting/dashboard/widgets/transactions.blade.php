@php
    $links = [
        [
            'url' => route('accounting.transactions.create'),
            'title' => __('app.register'),
            'icon' => 'plus-circle',
            'authorized' => Auth::user()->can('create', App\Models\Accounting\MoneyTransaction::class),
        ],
        [
            'url' => route('accounting.transactions.index'),
            'title' => __('app.overview'),
            'icon' => 'list',
            'authorized' =>  Auth::user()->can('list', App\Models\Accounting\MoneyTransaction::class),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('accounting.accounting'))

@section('widget-content')
    <table class="table mb-0">
        @forelse($wallets as $wallet)
            <tr>
                <td>
                    @icon(wallet) {{ $wallet->name }}
                </td>
                <td class="text-right">
                    <u>{{ number_format($wallet->calculatedSum(), 2) }}</u>
                </td>
            </tr>
        @empty
            <tr><td><em>@lang('app.no_data_available')</em></td></tr>
        @endforelse
    </table>
@endsection
