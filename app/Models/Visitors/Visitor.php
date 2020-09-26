<?php

namespace App\Models\Visitors;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    use NullableFields;

    protected $nullable = [
        'id_number',
        'place_of_residence',
        'organization',
        'left_at',
    ];

    protected $dates = [
        'entered_at',
        'left_at',
    ];

    /**
     * Scope a query to only include donors matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('id_number', 'LIKE', '%' . $filter . '%')
                    ->orWhere('place_of_residence', 'LIKE', '%' . $filter . '%')
                    ->orWhere('organization', 'LIKE', '%' . $filter . '%');
            });
        }

        return $query;
    }
}
