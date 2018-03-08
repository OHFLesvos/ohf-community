@extends('layouts.app_bottom_nav', [ 
    'bottom_nav_elements' => [
        [
            'url' => route('bank.withdrawal'),
            'label' => 'Withdrawal',
            'description' => 'Hand out drachmas, coupons, ...',
            'icon' => 'id-card',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.withdrawal' || $currentRouteName == 'bank.withdrawalSearch';
            },
            'authorized' => Gate::allows('do-bank-withdrawals'),
        ],
        [
            'url' => route('bank.deposit'),
            'label' => 'Deposit',
            'description' => 'Register drachmas returned from projects',
            'icon' => 'money',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'bank.deposit';
            },
            'authorized' => Gate::allows('do-bank-deposits'),
        ]
    ] 
])