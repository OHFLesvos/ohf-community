@php
    $links = [
        [
            'url' => route('bank.index'),
            'title' => __('people::people.go_to_bank'),
            'icon' => 'search',
            'authorized' => Gate::allows('do-bank-withdrawals'),
        ],
        [
            'url' => route('reporting.bank.withdrawals'),
            'title' => __('people::people.view_bank_report'),
            'icon' => 'line-chart',
            'authorized' => !Gate::allows('do-bank-withdrawals'),
        ],
    ];
@endphp

@extends('dashboard.widgets.base')

@section('widget-title', __('people::people.bank'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('people::people.served_n_persons_and_handed_out_n_today', [ 
                'persons' => Gate::allows('do-bank-withdrawals') ? '<a href="' . route('bank.withdrawalTransactions') . '">' . $persons . '</a>': $persons, 
                'coupons' => $coupons 
            ])
        </p>
    </div>
@endsection
