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
        'place_id',
        'phone',
        'mobile',
        'email',
        'website',
        'tax_number',
        'tax_office',
        'bank',
        'iban',
        'remarks',
    ];

    protected $nullable = [
        'category',
        'address',
        'place_id',
        'phone',
        'mobile',
        'email',
        'website',
        'tax_number',
        'tax_office',
        'bank',
        'iban',
        'remarks',
    ];

    /**
     * Get the transactions for the supplier.
     */
    public function transactions()
    {
        return $this->hasMany(MoneyTransaction::class);
    }

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
                    ->orWhere('category', 'LIKE', '%' . $filter . '%')
                    ->orWhere('remarks', 'LIKE', '%' . $filter . '%');
            });
        }

        return $query;
    }
}
