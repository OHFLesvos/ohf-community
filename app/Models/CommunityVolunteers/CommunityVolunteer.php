<?php

namespace App\Models\CommunityVolunteers;

use App\Models\Traits\CommentsRelation;
use Carbon\Carbon;
use Dyrynda\Database\Support\NullableFields;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class CommunityVolunteer extends Model implements Auditable
{
    use CommentsRelation;
    use HasFactory;
    use NullableFields;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'community_volunteers';

    protected $casts = [
        'languages' => 'array',
    ];

    protected $nullable = [
        'nickname',
        'date_of_birth',
        'gender',
        'nationality',
        'languages',
        'portrait_picture',
        'police_no',
        'local_phone',
        'other_phone',
        'whatsapp',
        'email',
        'skype',
        'residence',
        'notes',
    ];

    /**
     * @return Attribute<string,never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $str = '';
                if ($this->first_name != null) {
                    $str .= $this->first_name;
                }
                if ($this->family_name != null) {
                    $str .= ' '.strtoupper($this->family_name);
                }
                if ($this->nickname != null) {
                    if (! empty($str)) {
                        $str .= ' ';
                    }
                    $str .= '«'.$this->nickname.'»';
                }

                return trim($str);
            },
        );
    }

    /**
     * @return Attribute<?int,never>
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    return isset($this->date_of_birth) ? (new Carbon($this->date_of_birth))->age : null;
                } catch (Exception $e) {
                    Log::error('Error calculating age of '.$this->full_name.' ('.$this->date_of_birth.'): '.$e->getMessage());

                    return null;
                }
            },
        );
    }

    public function responsibilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Responsibility::class,
            'community_volunteer_responsibility',
            'community_volunteer_id',
            'responsibility_id'
        )
            ->using(CommunityVolunteerResponsibility::class)
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include community volunteers with a certain work status.
     */
    public function scopeWorkStatus(Builder $query, string $status): Builder
    {
        if ($status == 'active') {
            return $query->whereHas(
                'responsibilities',
                fn ($rqry) => $rqry
                    ->whereNotNull('community_volunteer_responsibility.start_date')
                    ->whereDate('community_volunteer_responsibility.start_date', '<=', today())
                    ->where(
                        fn ($q) => $q
                            ->whereNull('community_volunteer_responsibility.end_date')
                            ->orWhereDate('community_volunteer_responsibility.end_date', '>=', today())
                    )
            );
        }
        if ($status == 'alumni') {
            return $query->whereHas(
                'responsibilities',
                fn ($rqry) => $rqry
                    ->whereNotNull('community_volunteer_responsibility.end_date')
                    ->whereDate('community_volunteer_responsibility.end_date', '<', today())
            );
        }
        if ($status == 'future') {
            return $query->whereHas(
                'responsibilities',
                fn ($rqry) => $rqry
                    ->whereNull('community_volunteer_responsibility.start_date')
                    ->orWhereDate('community_volunteer_responsibility.start_date', '>', today())
            )->orDoesntHave('responsibilities');
        }
        throw new Exception('Unknown work status '.$status);
    }

    /**
     * @return Attribute<?Carbon,never>
     */
    protected function firstWorkStartDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->responsibilities
                    ->map(fn (Responsibility $e) => $e->getRelationValue('pivot')->start_date)->min() ?? null;
            },
        );
    }

    /**
     * @return Attribute<?Carbon,never>
     */
    protected function lastWorkEndDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->responsibilities
                    ->map(fn (Responsibility $e) => $e->getRelationValue('pivot')->end_date)->max() ?? null;
            },
        );
    }

    /**
     * @return Attribute<int,never>
     */
    protected function workingSinceDays(): Attribute
    {
        return Attribute::make(
            get: function () {
                $start = $this->first_work_start_date;
                $end = $this->last_work_end_date;
                if ($start !== null && $end !== null && $end->lte(today())) {
                    return $start->diffInDays($end);
                }
                if ($start !== null) {
                    return $start->diffInDays(today());
                }

                return 0;
            },
        );
    }

    /**
     * Returns a list of all genders assigned to any record.
     */
    public static function genders(?bool $includeEmpty = false): array
    {
        return self::select('gender')
            ->distinct()
            ->when(! $includeEmpty, fn ($qry) => $qry->whereNotNull('gender'))
            ->orderBy('gender')
            ->get()
            ->pluck('gender')
            ->toArray();
    }

    /**
     * Returns a list of all nationalities assigned to any record.
     */
    public static function nationalities(?bool $includeEmpty = false): array
    {
        return self::select('nationality')
            ->distinct()
            ->when(! $includeEmpty, fn ($qry) => $qry->whereNotNull('nationality'))
            ->orderBy('nationality')
            ->get()
            ->pluck('nationality')
            ->toArray();
    }

    /**
     * Returns a list of all languages assigned to any record.
     */
    public static function languages(?bool $includeEmpty = false): array
    {
        return self::select('languages')
            ->distinct()
            ->when(! $includeEmpty, fn ($qry) => $qry->whereNotNull('languages'))
            ->orderBy('languages')
            ->get()
            ->pluck('languages')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Returns a list of all pickup locations assigned to any record.
     */
    public static function pickupLocations(?bool $includeEmpty = false): array
    {
        return self::select('pickup_location')
            ->distinct()
            ->orderBy('pickup_location')
            ->when(! $includeEmpty, fn ($qry) => $qry->whereNotNull('pickup_location'))
            ->get()
            ->pluck('pickup_location')
            ->toArray();
    }
}
