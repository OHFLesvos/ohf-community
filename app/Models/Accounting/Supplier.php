<?php

namespace App\Models\Accounting;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use NullableFields;

    protected $fillable = [
        'name',
        'category',
        'address',
        'phone',
        'mobile',
        'email',
        'tax_number',
        'bank',
        'iban',
    ];

    protected $nullable = [
        'category',
        'address',
        'phone',
        'mobile',
        'email',
        'tax_number',
        'bank',
        'iban',
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
                return $wq->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('category', 'LIKE', '%' . $filter . '%');
            });
        }

        return $query;
    }
}
