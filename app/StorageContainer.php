<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Iatstuti\Database\Support\NullableFields;

class StorageContainer extends Model
{
    use Sluggable;
    use NullableFields;

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
        return $this->hasMany('App\StorageTransaction', 'container_id');
    }  
}
