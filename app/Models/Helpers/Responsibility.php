<?php

namespace App\Models\Helpers;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    use Sluggable;

    protected $table = 'helpers_responsibilities';

    protected $fillable = [
        'name',
        'capacity',
        'available',
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
                'source' => 'name',
            ],
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

    public function helpers()
    {
        return $this->belongsToMany(Helper::class, 'helpers_helper_responsibility', 'responsibility_id', 'helper_id')
            ->withTimestamps();
    }

    public function getIsCapacityExhaustedAttribute()
    {
        return $this->capacity != null && $this->capacity < $this->numberOfActiveHelpers;
    }

    public function getHasHelpersAltoughNotAvailableAttribute()
    {
        return ! $this->available && $this->numberOfActiveHelpers > 0;
    }

    public function getNumberOfActiveHelpersAttribute()
    {
        return $this->helpers()->active()->count();
    }

    /**
     * Scope a query to only include helpers matching the given filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter(Builder $query, string $filter)
    {
        return $query->where('name', 'LIKE', '%' . $filter . '%');
    }
}
