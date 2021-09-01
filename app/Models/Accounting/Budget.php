<?php

namespace App\Models\Accounting;

use App\Models\Fundraising\Donor;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function getBalance(): float
    {
        return $this->initial_amount + $this->transactions
            ->map(fn ($transaction) => $transaction->type == 'income' ? $transaction->amount : -$transaction->amount)
            ->sum();
    }

    /**
     * Scope a query to only include budgets matching the given filter conditions
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter)
    {
        if (empty($filter)) {
            return $query;
        }

        return $query->where(function (Builder $qry1) use ($filter) {
            return $qry1->where('name', 'LIKE', '%' . $filter . '%')
                ->orWhere('description', 'LIKE', '%' . $filter . '%');
        });
    }
}
