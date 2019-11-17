<?php

namespace Modules\Helpers\Entities;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Responsibility extends Model
{
    use Sluggable;

    protected $table = 'helpers_responsibilities';

    protected $fillable = [
        'name',
        'capacity',
        'available'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available' => 'boolean',
    ];

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

    function helpers()
    {
        return $this->belongsToMany(Helper::class, 'helpers_helper_responsibility', 'responsibility_id', 'helper_id')
            ->withTimestamps();;
    }

    function getIsCapacityExhaustedAttribute()
    {
        return $this->capacity != null && $this->capacity < $this->numberOfActiveHelpers;
    }

    function getHasHelpersAltoughNotAvailableAttribute()
    {
        return !$this->available && $this->numberOfActiveHelpers > 0;
    }

    function getNumberOfActiveHelpersAttribute()
    {
        return $this->helpers()->active()->count();
    }
}
