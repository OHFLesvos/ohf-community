<?php

namespace App\Support\Accounting;

use NumberFormatter;

trait FormatsCurrency
{
    protected function formatCurrency(?float $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $currency = setting('accounting.transactions.currency', null);
        $fmt = new NumberFormatter(app()->getLocale(), $currency !== null ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);

        return $currency !== null ? $fmt->formatCurrency($value, $currency) : $fmt->format($value);
    }
}
