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
        'today',
        'no_receipt',
        'wallet_owner',
        'controlled',
    ],
    'webling' => [
        'api' => [
            'url' => env('WEBLING_API_URL'),
            'key' => env('WEBLING_API_KEY'),
        ],
    ],
    'thumbnail_size' => 150,
    'max_image_size' => 1920,
];
