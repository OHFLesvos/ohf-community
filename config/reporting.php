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
        'bank-visitors' => [
            'route' => 'reporting.bank.visitors',
            'icon' => 'users',
            'gate' => 'view-bank-reports',
            'featured' => true,
        ],
        // TODO
        // 'fundraising' => [
        //     'route' => 'fundraising.report',
        //     'icon' => 'donate',
        //     'gate' => 'view-fundraising-reports',
        //     'featured' => true,
        // ],
        'library' => [
            'route' => 'library.report',
            'icon' => 'book',
            'gate' => 'operate-library',
            'featured' => false,
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
