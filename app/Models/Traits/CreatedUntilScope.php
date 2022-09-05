<?php

namespace App\Models\Traits;

trait CreatedUntilScope
{
    /**
     * Scope a query to only include records until a given date
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|Carbon|null  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedUntil($query, $date = null, $column = 'created_at')
    {
        if ($date !== null) {
            $query->whereDate($column, '<=', $date);
        }

        return $query;
    }
}
