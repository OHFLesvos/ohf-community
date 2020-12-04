<?php

namespace App\Models\CommunityVolunteers;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Responsibility extends Model
{
    use HasFactory;
    use Sluggable;

    protected $table = 'community_volunteer_responsibilities';

    protected $fillable = [
        'name',
        'description',
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
    public function sluggable(): array
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
            ->using(CommunityVolunteerResponsibility::class)
            ->withPivot([ 'start_date', 'end_date' ])
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
        return $this->communityVolunteers()->get()->filter(function($volunteer) {
            if ($volunteer->pivot->hasDateRange()) {
                return $volunteer->pivot->isInsideDateRange(Carbon::now());
            }
            return ($volunteer->work_starting_date == null || Carbon::now() >= $volunteer->work_starting_date)
                && ($volunteer->work_leaving_date == null || Carbon::now() <= $volunteer->work_leaving_date);
        })->count();
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
