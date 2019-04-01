<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cviebrock\EloquentSluggable\Sluggable;

use Iatstuti\Database\Support\NullableFields;

class InventoryStorage extends Model
{
    use Sluggable;
    use NullableFields;
    use SoftDeletes; // TODO might collide with slug

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
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

    protected $nullable = [
		'description',
    ];

    public function transactions() {
        return $this->hasMany('Modules\Inventory\Entities\InventoryItemTransaction', 'storage_id');
    }  
}
