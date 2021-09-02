<?php

namespace App\Support\Accounting;

use NumberFormatter;

trait FormatsCurrency
{
    protected function formatCurrency($value) {
        if ($value === null) {
            return null;
        }
        $currency = config('accounting.default_currency');
        if ($currency === null) {
            return $value;
        }
        $fmt = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($value, $currency);
    }
}
