<?php

namespace App\Support\Accounting;

use NumberFormatter;

trait FormatsCurrency
{
    protected function formatCurrency($value)
    {
        if ($value === null) {
            return null;
        }
        $currency = setting('accounting.transactions.currency');
        $fmt = new NumberFormatter(app()->getLocale(), $currency !== null ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);

        return $fmt->formatCurrency($value, $currency);
    }
}
