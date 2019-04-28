<?php

namespace Modules\Logistics\Entities;

use App\PointOfInterest;

use Illuminate\Database\Eloquent\Model;

use Iatstuti\Database\Support\NullableFields;

class Supplier extends Model
{
    use NullableFields;

    protected $table = 'logistics_suppliers';

    protected $fillable = [
        'category',
        'phone',
        'email',
        'website',
        'tax_number',
        'bank_account',
    ];

    protected $nullable = [
        'phone',
        'email',
        'website',
        'tax_number',
        'bank_account',
    ];

    /**
     * Get the PoI record associated with the supplier.
     */
    public function poi()
    {
        return $this->belongsTo(PointOfInterest::class);
    }

    /**
     * The products that belong to the supplier.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'logistics_product_supplier')
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
