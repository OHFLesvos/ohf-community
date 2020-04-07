<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'accounting_wallets';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the transactions for the wallet.
     */
    public function transactions()
    {
        return $this->hasMany(MoneyTransaction::class);
    }
}
