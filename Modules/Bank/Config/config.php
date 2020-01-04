<?php

return [
    'name' => 'Bank',
    'export_formats' => [
        'xlsx' => __('app.excel_xls'),
        'pdf' => __('app.pdf_pdf'),
    ],
    /**
     * Number of results which should be shown per page
     */
    'results_per_page' => 15,
    /**
     * Number of past weeks to consider when determing frequent visitors
     */
    'frequent_visitor_weeks' => 4,
    /**
     * Minimum number of visits required to be considered a frequent visitor
     */
    'frequent_visitor_threshold' => 12,
    /**
     * Grace period to allow undoing handing out of a coupon
     */
    'undo_coupon_handout_grace_period' => 60
];
