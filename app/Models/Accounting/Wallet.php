<?php

namespace App\Models\Accounting;

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
}
