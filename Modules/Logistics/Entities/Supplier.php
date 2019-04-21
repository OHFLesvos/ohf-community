<?php

namespace Modules\Logistics\Entities;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Supplier extends Model
{
    protected $table = 'logistics_suppliers';

    protected $fillable = [
        'category',
        'phone',
        'email',
        'website',
    ];

    protected $nullable = [
        'phone',
        'email',
        'website',
    ];

    /**
     * Get the PoI record associated with the supplier.
     */
    public function poi()
    {
        return $this->belongsTo('App\Poi');
    }

    /**
     * The products that belong to the supplier.
     */
    public function products()
    {
        return $this->belongsToMany('Modules\Logistics\Entities\Product', 'logistics_product_supplier')
            ->withPivot('remarks');
    }

    public static function boot()
    {
        static::deleting(function($model) {
            if ($model->poi != null) {
                $model->poi->delete();
            }
         });

        parent::boot();
    }
}
