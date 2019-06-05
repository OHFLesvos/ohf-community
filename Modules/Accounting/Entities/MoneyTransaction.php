<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use OwenIt\Auditing\Contracts\Auditable;

class MoneyTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public static function currentWallet(): float
    {
        $income = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'income')
            ->first())
            ->sum;

        $spending = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->where('type', 'spending')
            ->first())
            ->sum;

        return $income - $spending;
    }
}
