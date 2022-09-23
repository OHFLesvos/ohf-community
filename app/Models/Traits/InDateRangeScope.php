<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait InDateRangeScope
{
    /**
     * Scope a query to only include records in a given date range
     */
    public function scopeInDateRange(
        Builder $query,
        string|Carbon|null $dateFrom = null,
        string|Carbon|null $dateTo = null,
        string $column = 'created_at'
    ): Builder {
        if ($dateFrom !== null) {
            $query->whereDate($column, '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate($column, '<=', $dateTo);
        }

        return $query;
    }
}
