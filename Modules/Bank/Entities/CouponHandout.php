<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use OwenIt\Auditing\Contracts\Auditable;

use Iatstuti\Database\Support\NullableFields;

use Carbon\Carbon;

class CouponHandout extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use NullableFields;

    protected $fillable = [
        'date',
        'amount',
        'person_id',
        'coupon_type_id',
    ];

    protected $nullable = [
        'code',
        'code_redeemed',
    ];

    public function couponType()
    {
        return $this->belongsTo(CouponType::class);
    }

    public function person()
    {
        return $this->belongsTo(\App\Models\People\Person::class);
    }

    /**
     * {@inheritdoc}
     */
    public function generateTags(): array
    {
        return [
            $this->couponType->name,
        ];
    }

    public function isCodeExpired(): bool
    {
        if ($this->code_redeemed == null && $this->couponType->code_expiry_days != null) {
            $acceptDate = Carbon::today()->subDays($this->couponType->code_expiry_days - 1);
            if ($acceptDate->gt(new Carbon($this->date))) {
                return true;
            }
        }
        return false;
    }

    public function scopeWithCode($query, $code)
    {
        if (strlen($code) == 7) {
            return $query->where(DB::raw("SUBSTR(code, 1, 7)"), $code);
        } else {
            return $query->where('code', $code);
        }
    }

    public static function returningPossibleGracePeriod()
    {
        return \Setting::get('bank.undo_coupon_handout_grace_period', Config::get('bank.undo_coupon_handout_grace_period'));
    }

    public function getIsReturningPossibleAttribute()
    {
        return $this->created_at->diffInSeconds(Carbon::now()) < self::returningPossibleGracePeriod();
    }

    public function getReturGracePeriodAttribute()
    {
        return max(0, self::returningPossibleGracePeriod() - $this->created_at->diffInSeconds(Carbon::now()));
    }
}
