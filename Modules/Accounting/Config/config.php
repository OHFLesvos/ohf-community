<?php

return [
    'name' => 'Accounting',

    'filter_columns' => [
        'type',
        'category',
        'project',
        'beneficiary',
        'description',
        'receipt_no',
        'today',
        'no_receipt',
        'wallet_owner',
    ],

    'webling' => [
        'api' => [
            'url' => env('WEBLING_API_URL'),
            'key' => env('WEBLING_API_KEY'),
        ],
    ],
];
