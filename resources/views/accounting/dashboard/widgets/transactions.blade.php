@php
    $links = [
        [
            'url' => route('accounting.transactions.create'),
            'title' => __('app.register'),
            'icon' => 'plus-circle',
            'authorized' => Auth::user()->can('create', App\Models\Accounting\MoneyTransaction::class),
        ],
        [
            'url' => route('accounting.transactions.summary'),
            'title' => __('accounting.summary'),
            'icon' => 'calculator',
            'authorized' => Gate::allows('view-accounting-summary'),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('accounting.accounting'))

@section('widget-content')
    <table class="table mb-0">
        @isset($spending)
            <tr><td>@lang('accounting.spending') {{ $monthName }}</td><td class="text-right">{{ number_format($spending, 2) }}</td></tr>
        @endif
        @isset($wallet)
            <tr>
                <td>
                    @lang('accounting.wallet')
                </td>
                <td class="text-right">
                    <u>{{ number_format($wallet, 2) }}</u>
                </td>
            </tr>
        @else
            <tr><td><em>@lang('app.no_data_available')</em></td></tr>
        @endif
    </table>
@endsection
