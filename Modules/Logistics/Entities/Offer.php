<?php

namespace Modules\Logistics\Entities;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

class Offer extends Model
{
    use NullableFields;

    protected $table = 'logistics_offers';

    protected $fillable = [
        'unit',
        'price',
        'remarks',
        'product_id',
        'supplier_id',
    ];

    protected $nullable = [
        'unit',
        'price',
        'remarks',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
