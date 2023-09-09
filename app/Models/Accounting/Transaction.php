<?php

namespace App\Models\Accounting;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Transaction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'accounting_transactions';

    public const ADVANCED_FILTER_COLUMNS = [
        'type',
        'amount',
        'category_id',
        'secondary_category',
        'project_id',
        'location',
        'cost_center',
        'attendee',
        'description',
        'supplier',
        'receipt_no',
        'today',
        'no_receipt',
        'controlled',
        'remarks',
    ];

    protected $casts = [
        'booked' => 'boolean',
        'receipt_pictures' => 'array',
        'controlled_at' => 'datetime',
    ];

    /**
     * Get the wallet that owns this transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function controller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'controlled_by');
    }

    /**
     * Scope a query to only include transactions from a given date range
     */
    public function scopeForDateRange(
        Builder $query,
        string|Carbon $dateFrom = null,
        string|Carbon $dateTo = null
    ): Builder {
        if ($dateFrom !== null) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate('date', '<=', $dateTo);
        }

        return $query;
    }

    public function scopeForWallet(Builder $query, Wallet|int|null $wallet): Builder
    {
        if ($wallet === null) {
            return $query;
        }

        return $query->where('wallet_id', $wallet instanceof Wallet ? $wallet->id : $wallet);
    }

    /**
     * Scope a query to only include transactions matching the given filter conditions
     */
    public function scopeForFilter(Builder $query, string $filter): Builder
    {
        if (empty($filter)) {
            return $query;
        }

        return $query->where(fn (Builder $qry1) => $qry1
            ->where('receipt_no', $filter)
            ->when(is_numeric($filter), fn ($qi) => $qi->orWhere('amount', $filter))
            ->orWhereDate('date', $filter)
            ->orWhere('secondary_category', 'LIKE', '%'.$filter.'%')
            ->orWhere('location', 'LIKE', '%'.$filter.'%')
            ->orWhere('cost_center', 'LIKE', '%'.$filter.'%')
            ->orWhere('location', 'LIKE', '%'.$filter.'%')
            ->orWhere('attendee', 'LIKE', '%'.$filter.'%')
            ->orWhere('description', 'LIKE', '%'.$filter.'%')
            ->orWhere('remarks', 'LIKE', '%'.$filter.'%')
            ->orWhereHas('supplier', function (Builder $qry2) use ($filter) {
                $qry2->where('id', $filter)
                    ->orWhere('slug', $filter)
                    ->orWhere('name', 'LIKE', '%'.$filter.'%');
            })
            ->orWhereHas('category', function (Builder $qry2) use ($filter) {
                $qry2->where('name', 'LIKE', '%'.$filter.'%');
            })
            ->orWhereHas('project', function (Builder $qry2) use ($filter) {
                $qry2->where('name', 'LIKE', '%'.$filter.'%');
            })
        );
    }

    /**
     * Scope a query to only include transactions matching the given filter conditions
     */
    public function scopeForAdvancedFilter(Builder $query, array $filter): Builder
    {
        foreach (self::ADVANCED_FILTER_COLUMNS as $col) {
            if (! empty($filter[$col])) {
                if ($col == 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($col == 'controlled') {
                    if ($filter[$col] == 'yes') {
                        $query->whereNotNull('controlled_at');
                    } elseif ($filter[$col] == 'no') {
                        $query->whereNull('controlled_at');
                    }
                } elseif ($col == 'no_receipt') {
                    $query->where(function ($query) {
                        $query->whereNull('receipt_no');
                        $query->orWhereNull('receipt_pictures');
                        $query->orWhere('receipt_pictures', '[]');
                    });
                } elseif ($col == 'supplier') {
                    $query->whereHas('supplier', function (Builder $query) use ($filter, $col) {
                        $query->where('id', $filter[$col])
                            ->orWhere('slug', $filter[$col])
                            ->orWhere('name', 'like', '%'.$filter[$col].'%');
                    });
                } elseif ($col == 'attendee' || $col == 'description' || $col == 'remarks') {
                    $query->where($col, 'like', '%'.$filter[$col].'%');
                } else {
                    $query->where($col, $filter[$col]);
                }
            }
        }

        return $query;
    }

    public function removePicturePath(string $path): void
    {
        if (empty($this->receipt_pictures)) {
            return;
        }

        $this->receipt_pictures = collect($this->receipt_pictures)
            ->reject(fn ($value) => $value == $path)
            ->values()
            ->toArray();
    }

    public static function attendees(): array
    {
        return self::query()
            ->whereNotNull('attendee')
            ->distinct()
            ->orderBy('attendee')
            ->pluck('attendee')
            ->toArray();
    }

    public static function secondaryCategories(): array
    {
        return self::query()
            ->whereNotNull('secondary_category')
            ->distinct()
            ->orderBy('secondary_category')
            ->pluck('secondary_category')
            ->toArray();
    }

    public static function locations(): array
    {
        return self::query()
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location')
            ->toArray();
    }

    public static function costCenters(): array
    {
        return self::query()
            ->whereNotNull('cost_center')
            ->distinct()
            ->orderBy('cost_center')
            ->pluck('cost_center')
            ->toArray();
    }

    public static function years(): array
    {
        return self::query()
            ->selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->unique()
            ->toArray();
    }

    public function getIntermediateBalance()
    {
        return self::query()
            ->selectRaw('SUM(IF(type = \'income\', amount, -1 * amount)) as sum')
            ->forWallet($this->wallet_id)
            ->where('receipt_no', '<=', $this->receipt_no)
            ->orderBy('receipt_no', 'ASC')
            ->first('sum');
    }
}
