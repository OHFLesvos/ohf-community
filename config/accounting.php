<?php

return [
    'filter_columns' => [
        'type',
        'category',
        'secondary_category',
        'project',
        'location',
        'cost_center',
        'beneficiary',
        'description',
        'receipt_no',
        'receipt_no_correction',
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
