<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait CreatedUntilScope
{
    /**
     * Scope a query to only include records until a given date
     */
    public function scopeCreatedUntil(
        Builder $query,
        string|Carbon $date = null,
        string $column = 'created_at'
    ): Builder {
        if ($date !== null) {
            $query->whereDate($column, '<=', $date);
        }

        return $query;
    }
}
