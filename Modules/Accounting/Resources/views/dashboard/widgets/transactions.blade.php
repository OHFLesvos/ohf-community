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
        @isset($wallet)
            <tr>
                <td>
                    @lang('accounting::accounting.wallet')
                </td>
                <td class="text-right">
                    <u>{{ number_format($wallet, 2) }}</u>
                </td>
            </tr>
        @endif
    </table>
@endsection
