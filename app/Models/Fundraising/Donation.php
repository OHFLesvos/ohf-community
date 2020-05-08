<?php

namespace App\Models\Fundraising;

use App\Models\Traits\InDateRangeScope;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
    use NullableFields;
    use InDateRangeScope;

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

    /**
     * Scope a query to only include donations matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param null|string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('date', $filter)
                    ->orWhere('amount', $filter)
                    ->orWhereHas('donor', fn ($query) => $query->forSimpleFilter($filter))
                    ->orWhere('channel', 'LIKE', '%' . $filter . '%')
                    ->orWhere('purpose', 'LIKE', '%' . $filter . '%')
                    ->orWhere('reference', $filter)
                    ->orWhere('in_name_of', 'LIKE', '%' . $filter . '%');
            });
        }
        return $query;
    }

    /**
     * Gets a sorted list of all channels registered for donations
     *
     * @return array
     */
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
