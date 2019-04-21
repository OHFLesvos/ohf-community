<?php

namespace Modules\Logistics\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'logistics_products';

    protected $fillable = [
        'name',
        'name_translit',
        'category',
    ];

    protected $nullable = [
        'name_translit',
    ];

    public function getNameTrAttribute() {
        return $this->name_translit != null ? $this->name_translit : $this->name;
    }

    public function suppliers()
    {
        return $this->belongsToMany('Modules\Logistics\Entities\Supplier', 'logistics_product_supplier')
            ->withPivot('remarks');
    }

}
