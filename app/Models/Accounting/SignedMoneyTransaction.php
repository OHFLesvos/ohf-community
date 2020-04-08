<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SignedMoneyTransaction extends Model
{
    protected $table = 'accounting_signed_transactions';

    /**
     * Scope a query to only include transactions from a given date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Carbon|null  $dateFrom
     * @param  Carbon|null  $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDateRange($query, ?Carbon $dateFrom = null, ?Carbon $dateTo = null)
    {
        if ($dateFrom !== null) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate('date', '<=', $dateTo);
        }
    }

    /**
     * Scope a query to only include transactions for the given wallet
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Wallet $wallet
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForWallet($query, Wallet $wallet)
    {
        return $query->where('wallet_id', $wallet->id);
    }
}
