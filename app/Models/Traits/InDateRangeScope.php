<?php

namespace App\Models\Traits;

trait InDateRangeScope
{
    /**
     * Scope a query to only include records in a given date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|Carbon|null  $dateFrom
     * @param  string|Carbon|null  $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInDateRange($query, $dateFrom = null, $dateTo = null, $column = 'created_at')
    {
        if ($dateFrom !== null) {
            $query->whereDate($column, '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate($column, '<=', $dateTo);
        }

        return $query;
    }
}
