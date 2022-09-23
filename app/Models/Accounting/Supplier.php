<?php

namespace App\Models\Accounting;

use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the transactions for the supplier.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
