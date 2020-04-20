<?php

namespace App\Models\Fundraising;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                    ->orWhereHas('donor', function ($query) use ($filter) {
                        $query->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
                            ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
                            ->orWhere('company', 'LIKE', '%' . $filter . '%')
                            ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $filter . '%');
                    })
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
