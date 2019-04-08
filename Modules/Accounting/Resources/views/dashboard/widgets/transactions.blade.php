@php
    $links = [
        [
            'url' => route('accounting.transactions.create'),
            'title' => __('app.register'),
            'icon' => 'plus-circle',
            'authorized' => Auth::user()->can('create', Modules\Accounting\Entities\MoneyTransaction::class),
        ],
        [
            'url' => route('accounting.transactions.summary'),
            'title' => __('accounting::accounting.summary'),
            'icon' => 'calculator',
            'authorized' => Gate::allows('view-accounting-summary'),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('accounting::accounting.accounting'))

@section('widget-content')
    <table class="table mb-0">
        <tr><th colspan="2">{{ $monthName }}:</td></tr>
        @isset($income)
            <tr><td>@lang('accounting::accounting.income')</td><td class="text-right">{{ number_format($income, 2) }}</td></tr>
        @endif
        @isset($spending)
            <tr><td>@lang('accounting::accounting.spending')</td><td class="text-right">{{ number_format($spending, 2) }}</td></tr>
        @endif
        @if(!isset($income) && !isset($spending))
            <tr><td><em>@lang('app.no_data_available_in_the_selected_time_range')</em></td></tr>
        @endif
    </table>
@endsection
