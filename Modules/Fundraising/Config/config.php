<?php

return [
    'name' => 'Fundraising',
    'base_currency' => 'CHF',
    'currencies' => [
        'CHF' => 'CHF',
        'EUR' => 'EUR',
        'USD' => 'USD',
    ],
    'base_currency_excel_format' => '_ "CHF"\ * #,##0.00_ ;_ "CHF"\ * \-#,##0.00_ ;_ "CHF"\ * "-"??_ ;_ @_ ',
    'currencies_excel_format' => [
        'CHF' => '"CHF"\ #,##0.00;"CHF"\ \-#,##0.00',
        'EUR' => '#,##0.00\ [$€-407];\-#,##0.00\ [$€-407]',
        'USD' => '[$$-409]#,##0.00_ ;\-[$$-409]#,##0.00\ ',
    ],
];
