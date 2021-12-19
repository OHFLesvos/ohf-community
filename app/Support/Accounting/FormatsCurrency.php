<?php

namespace App\Support\Accounting;

use NumberFormatter;

trait FormatsCurrency
{
    protected function formatCurrency($value, ?string $currency = null): ?string {
        if ($value === null) {
            return null;
        }

        $currency = $currency ?? setting('accounting.transactions.currency');
        if ($currency === null) {
            return $value;
        }

        $fmt = new NumberFormatter(app()->getLocale(), $currency !== null ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);
        return $fmt->formatCurrency($value, $currency);
    }
}
