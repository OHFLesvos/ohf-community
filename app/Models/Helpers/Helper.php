<?php

namespace App\Models\Helpers;

use App\Models\People\Person;
use Carbon\Carbon;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Helper extends Model implements Auditable
{
    use SoftDeletes;
    use NullableFields;
    use \OwenIt\Auditing\Auditable;

    /**
     * Get the person record associated with the helper.
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'work_starting_date',
        'work_leaving_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $nullable = [
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

    public function responsibilities()
    {
        return $this->belongsToMany(Responsibility::class, 'helpers_helper_responsibility', 'helper_id', 'responsibility_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active helpers.
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
     * Scope a query to only include future helpers.
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
     * Scope a query to only include alumni (former helpers).
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
     * Scope a query to only include helpers matching the given filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter(Builder $query, string $filter)
    {
        return $query->where(fn (Builder $q) =>
            $q->whereHas('person', fn (Builder $query) => $query->forFilter($filter))
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
}
