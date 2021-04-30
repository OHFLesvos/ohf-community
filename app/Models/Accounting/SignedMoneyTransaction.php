<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SignedMoneyTransaction extends Model
{
    protected $table = 'accounting_signed_transactions';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope a query to only include transactions from a given date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|Carbon|null  $dateFrom
     * @param  string|Carbon|null  $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDateRange($query, $dateFrom = null, $dateTo = null)
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
