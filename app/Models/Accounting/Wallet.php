<?php

namespace App\Models\Accounting;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

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
        // Assign default marker if this is the first wallet being created
        static::creating(function ($model) {
            if (self::count() == 0) {
                $model->is_default = true;
            }
        });
        // Ensure only one wallet is default
        static::created(function ($model) {
            if ($model->is_default) {
                Wallet::where('id', '!=', $model->id)
                    ->update(['is_default' => false]);
            }
        });
        // Ensure only one wallet is default
        static::updated(function ($model) {
            if ($model->is_default) {
                Wallet::where('id', '!=', $model->id)
                    ->update(['is_default' => false]);
            }
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
        $result = MoneyTransaction::query()
            ->selectRaw('SUM(IF(type = \'income\', amount, -1*amount)) as amount_sum')
            ->selectRaw('SUM(fees) as fees_sum')
            ->forDateRange(null, $date)
            ->forWallet($this)
            ->first();

        return optional($result)->amount_sum - optional($result)->fees_sum;
    }

    /**
     * Gets the current amount (sum of all transactions) in the wallet
     *
     * @return float the amount in the wallet
     */
    public function getAmountAttribute(): float
    {
        return $this->calculatedSum() ?? 0;
    }

    public function getLatestActivityAttribute(): ?Carbon
    {
        return optional($this->transactions()
            ->orderBy('created_at', 'desc')
            ->first())->created_at;
    }

    public function getNextFreeReceiptNumberAttribute(): int
    {
        return optional(MoneyTransaction::selectRaw('MAX(receipt_no) as val')
            ->forWallet($this)
            ->first())
            ->val + 1;
    }

    /**
     * The roles that can access the wallet.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'accounting_wallet_role')
            ->withTimestamps();
    }
}
