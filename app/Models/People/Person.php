<?php

namespace App\Models\People;

use App\Models\Bank\CouponType;
use App\Models\Bank\CouponHandout;
use App\Models\Library\LibraryLending;
use App\Models\Helpers\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;

use Iatstuti\Database\Support\NullableFields;

class Person extends Model
{
    const PUBLIC_ID_LENGTH = 10;

    use SoftDeletes;
    use NullableFields;

    protected $table = 'persons';

    protected $dates = [
        'deleted_at',
        'card_issued',
    ];

    protected $fillable = [
        'name',
        'family_name',
        'gender',
        'date_of_birth',
        'police_no',
        'nationality',
        'languages',
        'languages_string',
        'remarks'
    ];

    protected $nullable = [
        'date_of_birth',
        'nickname',
        'portrait_picture',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->search = self::createSearchString($model);
            $model->public_id = strtolower(Str::random(self::PUBLIC_ID_LENGTH));
        });

        static::updating(function ($model) {
            $model->search = self::createSearchString($model);
        });

        static::deleting(function($model) {
            if ($model->helper != null) {
                $model->helper->delete();
            }
            if ($model->isForceDeleting()) {
                if ($model->portrait_picture != null) {
                    Storage::delete($model->portrait_picture);
                }
            }
         });

        parent::boot();
    }

    private static function createSearchString($model) {
        $str = $model->name . ' ' . $model->family_name;
        return preg_replace('/\s+/', ' ', trim($str));
    }

    /**
     * Get the helper record associated with the person.
     */
    public function helper()
    {
        return $this->hasOne(Helper::class, 'person_id', 'id');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'public_id';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'languages' => 'array',
    ];

    public function getAgeAttribute()
    {
        try {
            return isset($this->date_of_birth) ? (new Carbon($this->date_of_birth))->age : null;
        } catch (\Exception $e) {
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
        $this->languages = !empty($value) ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) : null;
    }

    function getFullNameAttribute()
    {
        $str = '';
        if ($this->name != null) {
            $str .= $this->name;
        }
        if ($this->family_name != null) {
            $str .= ' ' . strtoupper($this->family_name);
        }
        if ($this->nickname != null) {
            if (!empty($str)) {
                $str .= ' ';
            }
            $str .= '«'.$this->nickname.'»';
        }
        return trim($str);
    }

    public function getPoliceNoFormattedAttribute()
    {
        if ($this->police_no !== null && $this->police_no > 0) {
            return '05/' . sprintf("%09d", $this->police_no);
        }
        return null;
    }

    public function getFrequentVisitorAttribute()
    {
        $weeks = \Setting::get('bank.frequent_visitor_weeks', Config::get('bank.frequent_visitor_weeks'));
        $date = Carbon::today()->subWeek($weeks)->toDateString();
        $threshold = \Setting::get('bank.frequent_visitor_threshold', Config::get('bank.frequent_visitor_threshold'));
        $q = $this->couponHandouts()
            ->whereDate('date', '>=', $date)
            ->groupBy('date');
        $count = DB::table(DB::raw('('.$q->toSql().') as o2'))
            ->mergeBindings($q->getQuery()->getQuery())
            ->count();
            return $count >= $threshold;
    }

    function children()
    {
        if ($this->gender == 'm') {
            return $this->hasMany(Person::class, 'father_id');
        } else {
            return $this->hasMany(Person::class, 'mother_id');
        }
    }

    function mother()
    {
        return $this->belongsTo(Person::class);
    }

    function father()
    {
        return $this->belongsTo(Person::class);
    }

    function partner()
    {
        return $this->hasOne(Person::class, 'partner_id');
    }

    function getSiblingsAttribute()
    {
        $siblings = [];

        $mother = $this->mother;
        if ($mother != null) {
            $children = $mother->children;
            if ($children != null && $children->count() > 0) {
                $children->filter(function($m) { return $m->id != $this->id; })->each(function($c) use(&$siblings) {
                    $siblings[] = $c;
                });
            }
        }

        $father = $this->father;
        if ($father != null) {
            $children = $father->children;
            if ($children != null && $children->count() > 0) {
                $children->filter(function($m) { return $m->id != $this->id; })->each(function($c) use(&$siblings) {
                    $siblings[] = $c;
                });
            }
        }

        return collect($siblings);
    }

    function getPartnersChildrenAttribute()
    {
        $results = [];

        $ownChildrenIds = $this->children->pluck('id');

        $partner = $this->partner;
        if ($partner != null) {
            $children = $partner->children;
            if ($children != null && $children->count() > 0) {
                $children->filter(function($m) use ($ownChildrenIds) {
                        return $m->id != $this->id && !$ownChildrenIds->contains($m->id);
                    })->each(function($c) use(&$results) {
                        $results[] = $c;
                    });
            }
        }

        return collect($results);
    }

    function getFamilyAttribute()
    {
        $members = [$this];
        $mother = $this->mother;
        if ($mother != null) {
            $members[] = $mother;
        }
        $father = $this->father;
        if ($father != null) {
            $members[] = $father;
        }
        $partner = $this->partner;
        if ($partner != null) {
            $members[] = $partner;
        }
        $children = $this->children;
        if ($children != null && $children->count() > 0) {
            $children->each(function($c) use(&$members) {
                $members[] = $c;
            });
        }
        $partnersChildren = $this->partnersChildren;
        if ($partnersChildren != null && $partnersChildren->count() > 0) {
            $partnersChildren->each(function($c) use(&$members) {
                $members[] = $c;
            });
        }
        $this->siblings->each(function($c) use(&$members) {
            $members[] = $c;
        });
        return collect($members);
    }

    function getOtherFamilyMembersAttribute()
    {
        return $this->family->filter(function($m) { return $m->id != $this->id; });
    }

    function revokedCards()
    {
        return $this->hasMany(RevokedCard::class);
    }

    public function couponHandouts()
    {
        // TODO This is a circular module dependency
        return $this->hasMany(\App\Models\Bank\CouponHandout::class);
    }

    public function eligibleForCoupon(CouponType $couponType): bool
    {
        $age = $this->age;
        if ($age !== null) {
            if ($couponType->max_age != null && $age > $couponType->max_age) {
                return false;
            }
            if ($couponType->min_age != null && $age < $couponType->min_age) {
                return false;
            }
        }
        return $couponType->allow_for_helpers || !optional($this->helper)->isActive;
    }

    public function canHandoutCoupon(CouponType $couponType)
    {
        if ($couponType->newly_registered_block_days != null) {
            $registeredDay = $this->created_at->copy()->startOfDay();
            $thresholdDay = Carbon::today()->subDays($couponType->newly_registered_block_days);
            if ($registeredDay > $thresholdDay) {
                return trans_choice('people.recently_registerd_wait_n_days', $registeredDay->diffInDays($thresholdDay), ['days' => $registeredDay->diffInDays($thresholdDay)]);
            }
        }

        if ($couponType->retention_period == null) {
            $handout = $this->couponHandouts()
                ->where('coupon_type_id', $couponType->id)
                ->orderBy('date', 'desc')
                ->select('date')
                ->first();
            if ($handout != null) {
                return __('people.already_received');
            }
            return $this->checkDailySpendingLimit($couponType);
        }

        $retention_date = Carbon::today()->subDays($couponType->retention_period);
        $handout = $this->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->whereDate('date', '>', $retention_date)
            ->orderBy('date', 'desc')
            ->select('date')
            ->first();
        if ($handout != null) {
            $date = (new Carbon($handout->date));
            $daysUntil = ((clone $date)->addDays($couponType->retention_period))->diffInDays() + 1;
            return trans_choice('people.in_n_days', $daysUntil, ['days' => $daysUntil]);
        }
        return $this->checkDailySpendingLimit($couponType);
    }

    private function checkDailySpendingLimit($couponType)
    {
        if ($couponType->daily_spending_limit != null) {
            $handouts_today = CouponHandout::whereDate('date', Carbon::today())
                ->where('coupon_type_id', $couponType->id)
                ->count();
            if ($handouts_today >= $couponType->daily_spending_limit) {
                return __('people.daily_limit_reached');
            }
        }
    }

    public function lastCouponHandout(CouponType $couponType)
    {
        $handout = $this->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->orderBy('date', 'desc')
            ->select('date')
            ->first();
        if ($handout != null) {
            return (new Carbon($handout->date))->toDateString();
        }
        return null;
    }

    public function bookLendings()
    {
        return $this->hasMany(LibraryLending::class, 'person_id');
    }

    public function getHasOverdueBookLendingsAttribute()
    {
        return $this->bookLendings()->whereNull('returned_date')->whereDate('return_date', '<', Carbon::today())->count();
    }

    public static function getNationalities(): array
    {
        return collect(
            Person::select('nationality')
                ->selectRaw('COUNT(*) AS total')
                ->groupBy('nationality')
                ->whereNotNull('nationality')
                ->orderBy('total', 'DESC')
                ->get()
            )
            ->pluck('total', 'nationality')
            ->toArray();
    }

    public static function getGenderDistribution(): array
    {
        return collect(
            Person::select('gender')
                ->selectRaw('COUNT(*) AS total')
                ->groupBy('gender')
                ->whereNotNull('gender')
                ->get()
            )
            ->map(function($i){
                if ($i['gender'] == 'm') {
                    $i['gender'] = __('app.male');
                } else if ($i['gender'] == 'f') {
                    $i['gender'] = __('app.female');
                }
                return $i;
            })
            ->pluck('total', 'gender')
            ->toArray();
    }

    public static function getAgeDistribution(): array
    {
        return [
            '0 - 4' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            '5 - 11' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(12))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            '12 - 17' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(18))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(12))
                ->select('date_of_birth')
                ->count(),
            '18 - 24' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(25))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(18))
                ->select('date_of_birth')
                ->count(),
            '25 - 34' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(35))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(25))
                ->select('date_of_birth')
                ->count(),
            '35 - 44' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(45))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(35))
                ->select('date_of_birth')
                ->count(),
            '45 - 54' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(55))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(45))
                ->select('date_of_birth')
                ->count(),
            '55 - 64' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(65))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(55))
                ->select('date_of_birth')
                ->count(),
            '65 - 74' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(75))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(65))
                ->select('date_of_birth')
                ->count(),
            '75+' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(75))
                ->select('date_of_birth')
                ->count(),
        ];
    }

    public static function getRegistrationsPerDay(Carbon $dateFrom, Carbon $dateTo = null): array
    {
        if ($dateTo == null) {
            $dateTo = Carbon::today();
        }
        $registrations = Person::selectRaw('Date(created_at) as date')
            ->selectRaw('COUNT(*) as amount')
            ->where('created_at', '>=', $dateFrom->startOfDay())
            ->where('created_at', '<=', $dateTo->endOfDay())
            ->withTrashed()
			->groupBy('date')
			->orderBy('date', 'DESC')
            ->get()
            ->pluck('amount', 'date')
			->reverse()
            ->all();
        $date = $dateFrom->clone();
        do {
            $dateKey = $date->toDateString();
			if (!isset($registrations[$dateKey])) {
				$registrations[$dateKey] = 0;
			}
        } while ($date->addDay() <= $dateTo);
		ksort($registrations);
		return $registrations;
    }

    public static function getAvgRegistrationsPerDay(Carbon $dateFrom = null, Carbon $dateTo = null): int
    {
        $avg = DB::table(function ($query) use ($dateFrom, $dateTo) {
                $query->selectRaw('COUNT(*) AS total')
                    ->selectRaw('DATE(created_at) AS date')
                    ->from('persons')
                    ->groupBy('date');
                if ($dateFrom != null) {
                    $query->whereDate('created_at', '>=', $dateFrom->startOfDay());
                }
                if ($dateTo != null) {
                    $query->whereDate('created_at', '<=', $dateTo->endOfDay());
                }
            })
            ->avg('total');
        return round($avg);
    }

    /**
     * Scope to filter persons by name or ID numbers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $terms
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByTerms(Builder $query, array $terms)
    {
        return $query->orWhere(function($aq) use ($terms) {
            foreach ($terms as $term) {
                // Remove dash "-" from term
                $term = preg_replace('/^([0-9]+)-([0-9]+)/', '$1$2', $term);
                // Create like condition
                $aq->where(function($wq) use ($term) {
                    $wq->where('search', 'LIKE', '%' . $term . '%');
                    $wq->orWhere('police_no', $term);
                    $wq->orWhere('case_no_hash', DB::raw("SHA2('". $term ."', 256)"));
                    $wq->orWhere('card_no', $term);
                });
            }
        });
    }
}
