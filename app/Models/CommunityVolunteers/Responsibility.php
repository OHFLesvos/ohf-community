<?php

namespace App\Models\CommunityVolunteers;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    use Sluggable;

    protected $table = 'community_volunteer_responsibilities';

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

    public function communityVolunteers()
    {
        return $this->belongsToMany(CommunityVolunteer::class,
                'community_volunteer_responsibility',
                'responsibility_id',
                'community_volunteer_id'
            )
            ->withTimestamps();
    }

    public function getIsCapacityExhaustedAttribute()
    {
        return $this->capacity != null && $this->capacity < $this->countActive;
    }

    public function getHasAssignedAltoughNotAvailableAttribute()
    {
        return ! $this->available && $this->countActive > 0;
    }

    public function getCountActiveAttribute()
    {
        return $this->communityVolunteers()->workStatus('active')->count();
    }

    /**
     * Scope a query to only include responsibilities matching the given filter
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
