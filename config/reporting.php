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
            'icon' => 'money',
            'name' => 'Bank: Deposits',
            'gate' => 'view-bank-reports',
            'featured' => true,
        ],
        [
            'route' => 'reporting.kitchen',
            'icon' => 'spoon',
            'name' => 'Kitchen',
            'gate' => 'view-kitchen-reports',
            'featured' => true,
        ],
        [
            'route' => 'users.permissions',
            'icon' => 'key',
            'name' => 'User privileges',
            'gate' => 'view-usermgmt-reports',
            'featured' => true,
        ],
        [
            'route' => 'roles.permissions',
            'icon' => 'key',
            'name' => 'Role privileges',
            'gate' => 'view-usermgmt-reports',
            'featured' => false,
        ]
    ],

    'kitchen_project' => env('KITCHEN_PROJECT', 1),
];
