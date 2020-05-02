<?php

namespace App\Models\Traits;

trait RegisteredInDateRangeScope
{
    /**
     * Scope a query to only include records registered in a given date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|Carbon|null  $dateFrom
     * @param  string|Carbon|null  $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegisteredInDateRange($query, $dateFrom = null, $dateTo = null)
    {
        if ($dateFrom !== null) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        return $query;
    }
}
