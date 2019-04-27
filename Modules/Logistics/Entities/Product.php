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

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'logistics_product_supplier')
            ->withPivot('remarks');
    }

}
