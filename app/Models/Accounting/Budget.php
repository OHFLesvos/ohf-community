<?php

namespace App\Models\Accounting;

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;
    use NullableFields;

    protected $table = 'accounting_budgets';

    protected $fillable = [
        'name',
        'description',
        'agreed_amount',
        'initial_amount',
        'donor_id',
        'closed_at',
        'is_completed',
    ];

    protected $nullable = [
        'description',
        'donor_id',
        'closed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    protected $dates = [
        'closed_at',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getBalance(): float
    {
        $sumTransactions = $this->transactions
            ->map(fn ($transaction) => $transaction->type == 'income' ? $transaction->amount : -$transaction->amount)
            ->sum();

        return round($this->initial_amount + $sumTransactions, 2);
    }
}
