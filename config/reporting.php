<?php

return [
    'reports' => [
        [
            'route' => 'reporting.people',
            'icon' => 'users',
            'name' => 'People',
            'gate' => 'view-people-reports',
            'featured' => true,
        ],
        [
            'route' => 'reporting.bank.withdrawals',
            'icon' => 'id-card',
            'name' => 'Bank: Withdrawals',
            'gate' => 'view-bank-reports',
            'featured' => false,
        ],
        [
            'route' => 'reporting.bank.deposits',
            'icon' => 'money-bill-alt',
            'name' => 'Bank: Deposits',
            'gate' => 'view-bank-reports',
            'featured' => true,
        ],
        [
            'route' => 'reporting.kitchen',
            'icon' => 'utensil-spoon',
            'name' => 'Kitchen',
            'gate' => 'view-kitchen-reports',
            'featured' => true,
        ],
    ],

    'kitchen_project' => env('KITCHEN_PROJECT', 1),
];
