<?php

namespace App\Models\Accounting;

use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;
    use NullableFields;
    use Sluggable;

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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ],
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the transactions for the supplier.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope a query to only include donors matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('name', 'LIKE', '%'.$filter.'%')
                    ->orWhere('category', 'LIKE', '%'.$filter.'%')
                    ->orWhere('remarks', 'LIKE', '%'.$filter.'%')
                    ->orWhere('tax_number', $filter)
                    ->orWhere(DB::raw('REPLACE(phone, \' \', \'\')'), str_replace(' ', '', $filter))
                    ->orWhere(DB::raw('REPLACE(mobile, \' \', \'\')'), str_replace(' ', '', $filter))
                    ->orWhere(DB::raw('REPLACE(iban, \' \', \'\')'), str_replace(' ', '', $filter));
            });
        }

        return $query;
    }
}
