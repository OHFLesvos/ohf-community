@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('bank.withdrawal'),
            'label' => __('people.withdrawal'),
            'icon' => 'id-card',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.withdrawal'
                    || $currentRouteName == 'bank.withdrawalSearch'
                    || $currentRouteName == 'bank.registerCard'
                    || $currentRouteName == 'bank.showCard';
            },
            'authorized' => Gate::allows('do-bank-withdrawals'),
        ],
        [
            'url' => route('bank.deposit'),
            'label' => __('people.deposit'),
            'icon' => 'money',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.deposit';
            },
            'authorized' => Gate::allows('do-bank-deposits'),
        ]
    ] 
])