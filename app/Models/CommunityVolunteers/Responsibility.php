<?php

namespace App\Models\CommunityVolunteers;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function communityVolunteers(): BelongsToMany
    {
        return $this->belongsToMany(
            CommunityVolunteer::class,
            'community_volunteer_responsibility',
            'responsibility_id',
            'community_volunteer_id'
        )
            ->using(CommunityVolunteerResponsibility::class)
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }

    /**
     * @return Attribute<bool,never>
     */
    protected function isCapacityExhausted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->capacity !== null && $this->capacity < $this->countActive;
            },
        );
    }

    /**
     * @return Attribute<bool,never>
     */
    protected function hasAssignedAlthoughNotAvailable(): Attribute
    {
        return Attribute::make(
            get: function () {
                ! $this->available && $this->countActive > 0;
            },
        );
    }

    /**
     * @return Attribute<int,never>
     */
    protected function countActive(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->communityVolunteers
                    ->filter(fn (CommunityVolunteer $volunteer) => $volunteer->getRelationValue('pivot')->isInsideDateRange(now()))
                    ->count();
            },
        );
    }
}
