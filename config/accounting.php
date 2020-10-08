<?php

return [
    'filter_columns' => [
        'type',
        'category',
        'secondary_category',
        'project',
        'location',
        'cost_center',
        'attendee',
        'description',
        'supplier',
        'receipt_no',
        'today',
        'no_receipt',
        'controlled',
    ],
    'webling' => [
        'api' => [
            'url' => env('WEBLING_API_URL'),
            'key' => env('WEBLING_API_KEY'),
        ],
    ],
    'thumbnail_size' => 140,
    'max_image_size' => 1920,
];
