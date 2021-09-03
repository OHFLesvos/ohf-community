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
    ];

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Gets the amount of the wallet
     *
     * @param \Carbon\Carbon $date optional end-date until which transactions should be considered
     */
    public function calculatedSum(?Carbon $date = null): ?float
    {
        $result = Transaction::query()
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as amount_sum')
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
        return optional(Transaction::selectRaw('MAX(receipt_no) as val')
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
