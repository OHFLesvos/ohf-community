<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use OwenIt\Auditing\Contracts\Auditable;

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

    public static function currentWallet(): float
    {
        $income = optional(MoneyTransaction::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'income')
            ->first())
            ->sum;

        $spending = optional(MoneyTransaction::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'spending')
            ->first())
            ->sum;

        return $income - $spending;
    }

    public static function getNextFreeReceiptNo()
    {
        return optional(MoneyTransaction::select(DB::raw('MAX(receipt_no) as val'))
                ->first())
                ->val + 1;
    }    
}
