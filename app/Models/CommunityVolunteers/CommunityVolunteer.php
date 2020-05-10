<?php

namespace App\Models\CommunityVolunteers;

use Carbon\Carbon;
use Exception;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class CommunityVolunteer extends Model implements Auditable
{
    use NullableFields;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'community_volunteers';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'work_starting_date',
        'work_leaving_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
        'work_starting_date',
        'work_leaving_date',
        'notes',
    ];

    public function getFullNameAttribute()
    {
        $str = '';
        if ($this->first_name != null) {
            $str .= $this->first_name;
        }
        if ($this->family_name != null) {
            $str .= ' ' . strtoupper($this->family_name);
        }
        if ($this->nickname != null) {
            if (! empty($str)) {
                $str .= ' ';
            }
            $str .= '«'.$this->nickname.'»';
        }
        return trim($str);
    }

    public function getAgeAttribute()
    {
        try {
            return isset($this->date_of_birth) ? (new Carbon($this->date_of_birth))->age : null;
        } catch (Exception $e) {
            Log::error('Error calculating age of ' . $this->full_name . ' ('. $this->date_of_birth . '): ' . $e->getMessage());
            return null;
        }
    }

    public function getLanguagesStringAttribute()
    {
        return is_array($this->languages) ? implode(', ', $this->languages) : $this->languages;
    }

    public function setLanguagesStringAttribute($value)
    {
        $this->languages = ! empty($value) ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) : null;
    }

    public function responsibilities()
    {
        return $this->belongsToMany(Responsibility::class,
                'community_volunteer_responsibility',
                'community_volunteer_id',
                'responsibility_id'
            )
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active community volunteers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query
            ->whereNotNull('work_starting_date')
            ->whereDate('work_starting_date', '<=', Carbon::today())
            ->where(function ($q) {
                return $q->whereNull('work_leaving_date')
                    ->orWhereDate('work_leaving_date', '>=', Carbon::today());
            });
    }

    /**
     * Scope a query to only include future community volunteers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture($query)
    {
        return $query
            ->whereNull('work_starting_date')
            ->orWhereDate('work_starting_date', '>', Carbon::today());
    }


    /**
     * Scope a query to only include alumni (former community volunteers).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlumni($query)
    {
        return $query
            ->whereNotNull('work_leaving_date')
            ->whereDate('work_leaving_date', '<', Carbon::today());
    }

    public function getIsActiveAttribute() {
        return $this->work_starting_date != null && $this->work_leaving_date == null;
    }

    public function getWorkingSinceDaysAttribute() {
        $start = $this->work_starting_date != null ? new Carbon($this->work_starting_date) : null;
        $end = $this->work_leaving_date != null ? new Carbon($this->work_leaving_date) : null;
        if ($start != null && $end != null && $end->lte(Carbon::today())) {
            return $start->diffInDays($end);
        }
        if ($start != null) {
            return $start->diffInDays(Carbon::today());
        }
        return 0;
    }

    public static function pickupLocations(): array
    {
        return self::select('pickup_location')
            ->distinct()
            ->orderBy('pickup_location')
            ->whereNotNull('pickup_location')
            ->get()
            ->pluck('pickup_location')
            ->toArray();
    }

    /**
     * Scope a query to only include community volunteers matching the given filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter(Builder $query, string $filter)
    {
        return $query->where(fn (Builder $q) =>$q->where(DB::raw('CONCAT(first_name, \' \', family_name)'), 'LIKE', '%' . $filter . '%')
            ->orWhere(DB::raw('CONCAT(family_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
            ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
            ->orWhere('nickname', 'LIKE', '%' . $filter . '%')
            ->orWhere('family_name', 'LIKE', '%' . $filter . '%')
            ->orWhere('date_of_birth', $filter)
            ->orWhere('nationality', 'LIKE', '%' . $filter . '%')
            ->orWhere('police_no', $filter)
            ->orWhere('languages', 'LIKE', '%' . $filter . '%')
            ->orWhereHas('responsibilities', fn (Builder $query) => $query->forFilter($filter))
            ->orWhere('local_phone', 'LIKE', '%' . $filter . '%')
            ->orWhere('other_phone', 'LIKE', '%' . $filter . '%')
            ->orWhere('whatsapp', 'LIKE', '%' . $filter . '%')
            ->orWhere('email', 'LIKE', '%' . $filter . '%')
            ->orWhere('skype', 'LIKE', '%' . $filter . '%')
            ->orWhere('residence', 'LIKE', '%' . $filter . '%')
            ->orWhere('pickup_location', 'LIKE', '%' . $filter . '%')
            ->orWhere('notes', 'LIKE', '%' . $filter . '%')
        );
    }

    /**
     * Returns a list of all genders assigned to any record.
     *
     * @return array
     */
    public static function genders(): array
    {
        return self::select('gender')
            ->distinct()
            ->whereNotNull('gender')
            ->orderBy('gender')
            ->get()
            ->pluck('gender')
            ->push(null)
            ->toArray();
    }

    /**
     * Scope a query to only include community volunteers having the given gender
     *
     * @param Builder $query
     * @param string|null $gender
     * @return Builder
     */
    public function scopeHasGender(Builder $query, ?string $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Returns a list of all nationalities assigned to any record.
     *
     * @return array
     */
    public static function nationalities(): array
    {
        return self::select('nationality')
            ->distinct()
            ->whereNotNull('nationality')
            ->orderBy('nationality')
            ->get()
            ->pluck('nationality')
            ->toArray();
    }

    /**
     * Scope a query to only include community volunteers having the given nationality
     *
     * @param Builder $query
     * @param string|null $nationality
     * @return Builder
     */
    public function scopeHasNationality(Builder $query, ?string $nationality)
    {
        return $query->where('nationality', $nationality);
    }

    /**
     * Returns a list of all languages assigned to any record.
     *
     * @return array
     */
    public static function languages(): array
    {
        return self::select('languages')
            ->distinct()
            ->whereNotNull('languages')
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
     * Scope a query to only include community volunteers speaking the given language
     *
     * @param Builder $query
     * @param string|null $language
     * @return Builder
     */
    public function scopeSpeaksLanguage(Builder $query, ?string $language)
    {
        return $query->where('languages', 'like', '%"' . $language . '"%');
    }
}
