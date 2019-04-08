<?php

return [
    'reports' => [
        'monthly_summary' => [
            'route' => 'reporting.monthly-summary',
            'icon' => 'book',
            'gate' => 'view-people-reports',
            'featured' => true,
        ], 
        'people' => [
            'route' => 'reporting.people',
            'icon' => 'users',
            'gate' => 'view-people-reports',
            'featured' => true,
        ],
        'bank-withdrawals' => [
            'route' => 'reporting.bank.withdrawals',
            'icon' => 'id-card',
            'gate' => 'view-bank-reports',
            'featured' => false,
        ],
        'bank-deposits' => [
            'route' => 'reporting.bank.deposits',
            'icon' => 'money',
            'gate' => 'view-bank-reports',
            'featured' => true,
        ],
        'user-privileges' => [
            'route' => 'users.permissions',
            'icon' => 'key',
            'gate' => 'view-usermgmt-reports',
            'featured' => true,
        ],
        'role-privileges' => [
            'route' => 'roles.permissions',
            'icon' => 'key',
            'gate' => 'view-usermgmt-reports',
            'featured' => false,
        ],
        'privacy' => [
            'route' => 'reporting.privacy',
            'icon' => 'eye',
            'gate' => 'view-usermgmt-reports',
            'featured' => true,
        ],
    ],
];
