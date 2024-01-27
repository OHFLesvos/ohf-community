<?php

namespace App\Models\Accounting;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'accounting_wallets';

    protected $fillable = [
        'name',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Gets the amount of the wallet
     *
     * @param  \Carbon\Carbon  $date  optional end-date until which transactions should be considered
     */
    public function calculatedSum(?Carbon $date = null): ?float
    {
        $result = Transaction::query()
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as amount_sum')
            ->selectRaw('SUM(fees) as fees_sum')
            ->forDateRange(null, $date)
            ->forWallet($this->id)
            ->first();

        return optional($result)->amount_sum - optional($result)->fees_sum;
    }

    /**
     * Gets the current amount (sum of all transactions) in the wallet
     *
     * @return Attribute<float,never>
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calculatedSum() ?? 0.0,
        );
    }

    public function getNextFreeReceiptNumber(): int
    {
        return optional(Transaction::query()
            ->selectRaw('MAX(receipt_no) as val')
            ->forWallet($this->id)
            ->first())
            ->val + 1;
    }

    /**
     * The roles that can access the wallet.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'accounting_wallet_role')
            ->withTimestamps();
    }
}
