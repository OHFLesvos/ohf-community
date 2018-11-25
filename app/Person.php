<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\CouponType;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class Person extends Model
{
    use SoftDeletes;
    use NullableFields;
    
    protected $table = 'persons';

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'family_name',
        'police_no',
        'case_no',
        'nationality',
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
            $model->public_id = self::createUUID();
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

    public static function createUUID() {
        return bin2hex(random_bytes(16));
    }
    
    private static function createSearchString($model) {
        $str = $model->family_name . ' ' . $model->name;
        return preg_replace('/\s+/', ' ', trim($str));
    }
    
    /**
     * Get the helper record associated with the person.
     */
    public function helper()
    {
        return $this->hasOne('App\Helper', 'person_id', 'id');
    }

    // /**
    //  * Get the route key for the model.
    //  *
    //  * @return string
    //  */
    // public function getRouteKeyName()
    // {
    //     return 'public_id';
    // }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'languages' => 'array',
    ];    

    public function getAgeAttribute() {
        try {
            return isset($this->date_of_birth) ? (new Carbon($this->date_of_birth))->age : null;
        } catch (\Exception $e) {
            Log::error('Error calculating age of ' . $this->family_name . ' ' . $this->name . ' ('. $this->date_of_birth . '): ' . $e->getMessage());
            return null;
        }
    }

    function getFullNameAttribute() {
        $str = '';
        if ($this->name != null) {
            $str .= $this->name;
        }
        if ($this->family_name != null) {
            $str .= ' ' . $this->family_name;
        }
        if ($this->nickname != null) {
            if (!empty($str)) {
                $str .= ' ';
            }
            $str .= '«'.$this->nickname.'»';
        }
        return trim($str);
    }

    public function setCaseNoAttribute($value) {
        if ($value !== null && $value != '') {
            $value = preg_replace("/[^0-9]/", "", $value);
            if ($value <= 0) {
                $this->case_no_hash = null;
            } else {
                $this->case_no_hash = hash('sha256', $value);
            }
        }
    }

    public function getFrequentVisitorAttribute() {
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

    function children() {
        if ($this->gender == 'm') {
            return $this->hasMany('App\Person', 'father_id');
        } else {
            return $this->hasMany('App\Person', 'mother_id');
        }
    }

    function mother() {
        return $this->belongsTo('App\Person');
    }

    function father() {
        return $this->belongsTo('App\Person');
    }
    
    function partner() {
        return $this->hasOne('App\Person', 'partner_id');
    }

    function getSiblingsAttribute() {
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

        $partner = $this->partner;
        if ($partner != null) {
            $children = $partner->children;
            if ($children != null && $children->count() > 0) {
                $children->filter(function($m) { return $m->id != $this->id; })->each(function($c) use(&$siblings) {
                    $siblings[] = $c;
                });
            }
        }

        return collect($siblings);
    }

    function getFamilyAttribute() {
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
        $this->siblings->each(function($c) use(&$members) {
            $members[] = $c;
        });
        return collect($members);
    }

    function getOtherFamilyMembersAttribute() {
        return $this->family->filter(function($m) { return $m->id != $this->id; });
    }

    function revokedCards() {
        return $this->hasMany('App\RevokedCard');
    }

    public function couponHandouts() {
        return $this->hasMany('App\CouponHandout');
    }

    public function eligibleForCoupon(CouponType $couponType): bool {
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

    public function canHandoutCoupon(CouponType $couponType) {
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

    private function checkDailySpendingLimit($couponType) {
        if ($couponType->daily_spending_limit != null) {
            $handouts_today = CouponHandout
                ::whereDate('date', Carbon::today())
                ->where('coupon_type_id', $couponType->id)
                ->count();
            if ($handouts_today >= $couponType->daily_spending_limit) {
                return __('people.daily_limit_reached');
            }
        }
    }

    public function lastCouponHandout(CouponType $couponType) {
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
}
