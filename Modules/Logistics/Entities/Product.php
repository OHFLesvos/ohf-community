<?php

namespace Modules\Logistics\Entities;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

class Product extends Model
{
    use NullableFields;

    protected $table = 'logistics_products';

    protected $fillable = [
        'name',
        'name_local',
        'category',
    ];

    protected $nullable = [
        'name_local',
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public static function getCategories() {
        return self::select('category')
            ->orderBy('category')
            ->distinct()
            ->get()
            ->pluck('category')
            ->toArray();
    }
}
