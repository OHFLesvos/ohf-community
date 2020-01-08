@extends('layouts.tabbed_view', [
    'nav_elements' => [
        [
            'url' => route('bank.withdrawal'),
            'label' => __('people::people.withdrawal'),
            'icon' => 'id-card',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.withdrawal';
            },
            'authorized' => Gate::allows('do-bank-withdrawals'),
        ],
        [
            'url' => route('bank.deposit'),
            'label' => __('people::people.deposit'),
            'icon' => 'money-bill-alt',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.deposit';
            },
            'authorized' => Gate::allows('do-bank-deposits'),
        ]
    ]
])