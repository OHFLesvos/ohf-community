<?php

namespace App\Models\Fundraising;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use NullableFields;

    protected $nullable = [
        'purpose',
        'reference',
        'in_name_of',
    ];

    protected $dates = [
        'deleted_at',
        'thanked',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Scope a query to only include donations from the given year, if specified.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  null|int $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForYear($query, ?int $year)
    {
        if ($year !== null) {
            $query->whereYear('date', $year);
        }
        return $query;
    }

    public static function channels(): array
    {
        return self::select('channel')
            ->distinct()
            ->orderBy('channel')
            ->get()
            ->pluck('channel')
            ->toArray();
    }
}
