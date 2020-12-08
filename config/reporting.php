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
        'fundraising' => [
            'url' => '/fundraising/report',
            'icon' => 'donate',
            'gate' => 'view-fundraising-reports',
            'featured' => true,
        ],
        'library' => [
            'url' => '/library/report',
            'icon' => 'book',
            'gate' => 'operate-library',
            'featured' => false,
        ],
    ],
];
