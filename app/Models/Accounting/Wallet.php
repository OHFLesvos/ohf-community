<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'accounting_wallets';

    protected $fillable = [
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->is_default = self::count() == 0;
        });
        parent::boot();
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(MoneyTransaction::class);
    }

    /**
     * Gets the amount of the wallet
     *
     * @param \Carbon\Carbon $date optional end-date until which transactions should be considered
     */
    public function calculatedSum(?Carbon $date = null): ?float
    {
        $result = SignedMoneyTransaction::selectRaw('SUM(amount) as sum')
            ->forDateRange(null, $date)
            ->forWallet($this)
            ->first();

        return optional($result)->sum;
    }

    public function getAmountAttribute(): ?float
    {
        return $this->calculatedSum() ?? 0;
    }

}
