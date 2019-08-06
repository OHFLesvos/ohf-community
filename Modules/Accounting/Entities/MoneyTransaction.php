<?php

namespace Modules\Accounting\Entities;

use Modules\Accounting\Entities\SignedMoneyTransaction;
use Modules\Accounting\Support\Webling\Entities\Entrygroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use OwenIt\Auditing\Contracts\Auditable;

use Carbon\Carbon;

class MoneyTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->receipt_no = self::getNextFreeReceiptNo();
        });

        static::deleting(function($model) {
            if ($model->receipt_picture != null) {
                Storage::delete($model->receipt_picture);
            }
        });

        parent::boot();
    }

    protected $casts = [
        'booked' => 'boolean',
    ];

    /**
     * Gets the amount of the wallet
     * 
     * @param \Carbon\Carbon $date optional end-date until which transactions should be considered
     */
    public static function currentWallet(Carbon $date = null): ?float
    {
        $qry = SignedMoneyTransaction::select(DB::raw('SUM(amount) as sum'));

        self::dateFilter($qry, null, $date);

        return optional($qry->first())->sum;
    }

    public static function revenueByField(string $field, Carbon $dateFrom = null, Carbon $dateTo = null): Collection
    {
        $qry = SignedMoneyTransaction::select($field, DB::raw('SUM(amount) as sum'))
            ->groupBy($field)
            ->orderBy($field);

        self::dateFilter($qry, $dateFrom, $dateTo);

        return $qry->get()
            ->map(function($e) use ($field) {
                return [
                    'name'   => $e->$field,
                    'amount' => $e->sum,
                ];
            });
    }

    public static function totalSpending(Carbon $dateFrom = null, Carbon $dateTo = null): ?float
    {
        $qry = MoneyTransaction::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'spending');
        
        self::dateFilter($qry, $dateFrom, $dateTo);
        
        return optional($qry->first())->sum;
    }

    public static function totalIncome(Carbon $dateFrom = null, Carbon $dateTo = null): ?float
    {
        $qry = MoneyTransaction::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'income');
        
        self::dateFilter($qry, $dateFrom, $dateTo);
        
        return optional($qry->first())->sum;
    }

    private static function dateFilter($qry, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        if ($dateFrom != null) {
            $qry->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo != null) {
            $qry->whereDate('date', '<=', $dateTo);
        }
    }

    public static function getNextFreeReceiptNo()
    {
        return optional(MoneyTransaction::select(DB::raw('MAX(receipt_no) as val'))
            ->first())
            ->val + 1;
    }

    public function getExternalUrlAttribute()
    {
        if ($this->external_id != null)
        {
            return optional(Entrygroup::find($this->external_id))->url();
        }
        return null;
    }
}
