<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\CouponType;
use Iatstuti\Database\Support\NullableFields;

class Person extends Model
{
    use SoftDeletes;
    use NullableFields;
    
    protected $table = 'persons';

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['name', 'family_name', 'police_no', 'case_no', 'nationality', 'remarks'];
    
    protected $nullable = [
		'date_of_birth',
    ];

    const FREQUENT_VISITOR_WEEKS = 4;
    const FREQUENT_VISITOR_THRESHOLD = 12;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->search = self::createSearchString($model);
            $model->public_id = self::createUUID();
        });

        static::updating(function ($model) {
            $model->search = self::createSearchString($model);
        });
        
        parent::boot();
    }

    public static function createUUID() {
        return bin2hex(random_bytes(16));
    }
    
    private static function createSearchString($model) {
        $str = $model->family_name . ' ' . $model->name . ' ' . $model->police_no . ' ' . $model->case_no. ' ' . $model->medical_no. ' ' . $model->registration_no. ' ' . $model->section_card_no . ' ' . $model->temp_no;
        return preg_replace('/\s+/', ' ', trim($str));
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

    public function getAgeAttribute() {
        return isset($this->date_of_birth) ? (new Carbon($this->date_of_birth))->age : null;
    }

    public function getFrequentVisitorAttribute() {
        $weeks = \Setting::get('bank.frequent_visitor_weeks', self::FREQUENT_VISITOR_WEEKS);
        $threshold = \Setting::get('bank.frequent_visitor_threshold', self::FREQUENT_VISITOR_THRESHOLD);
        return $this->couponHandouts()
            ->whereDate('date', '>=', Carbon::today()->subWeek($weeks)->toDateString())
            ->groupBy('date')
            ->count() >= $threshold;
            // TODO validate
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
        return true;
    }

    public function canHandoutCoupon(CouponType $couponType) {
        $retention_date = Carbon::today()->subDays($couponType->retention_period);
        $handout = $this->couponHandouts()
            ->where('coupon_type_id', $couponType->id)
            ->whereDate('date', '>', $retention_date)
            ->first();
        if ($handout != null) {
            return new Carbon($handout->date);
        }
        return null;
    }
}
