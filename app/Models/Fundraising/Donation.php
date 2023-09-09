<?php

namespace App\Models\Fundraising;

use App\Models\Accounting\Budget;
use App\Models\Traits\CreatedUntilScope;
use App\Models\Traits\InDateRangeScope;
use Carbon\Carbon;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use CreatedUntilScope;
    use HasFactory;
    use InDateRangeScope;
    use NullableFields;

    protected $nullable = [
        'purpose',
        'reference',
        'in_name_of',
    ];

    protected $casts = [
        'amount' => 'float',
        'exchange_amount' => 'float',
        'deleted_at' => 'datetime',
        'thanked' => 'datetime:Y-m-d',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Scope a query to only include donations from the given year, if specified.
     */
    public function scopeForYear(Builder $query, ?int $year): Builder
    {
        if ($year !== null) {
            $query->whereYear('date', '=', $year);
        }

        return $query;
    }

    /**
     * Scope a query to only include donations matching the given filter
     * If no filter is specified, all records will be returned.
     */
    public function scopeForFilter(Builder $query, ?string $filter = ''): Builder
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('date', $filter)
                    ->when(is_numeric($filter), fn ($qi) => $qi->orWhere('amount', $filter))
                    ->when(is_numeric($filter), fn ($qi) => $qi->orWhere('exchange_amount', $filter))
                    ->orWhereHas('donor', fn ($query) => $query->forSimpleFilter($filter))
                    ->orWhere('channel', 'LIKE', '%'.$filter.'%')
                    ->orWhere('purpose', 'LIKE', '%'.$filter.'%')
                    ->orWhere('reference', $filter)
                    ->orWhere('in_name_of', 'LIKE', '%'.$filter.'%');
            });
        }

        return $query;
    }

    /**
     * Gets a sorted list of all channels registered for donations
     */
    public static function channels(): array
    {
        return self::query()
            ->distinct()
            ->orderBy('channel')
            ->pluck('channel')
            ->toArray();
    }

    /**
     * Gets an array of all channels assigned to donations, grouped and ordered by amount
     */
    public static function channelDistribution(Carbon $untilDate = null): array
    {
        return self::query()
            ->select('channel')
            ->selectRaw('COUNT(*) as channels_count')
            ->groupBy('channel')
            ->whereNotNull('channel')
            ->createdUntil($untilDate)
            ->orderBy('channels_count', 'desc')
            ->pluck('channels_count', 'channel')
            ->map(fn ($value) => floatval($value))
            ->toArray();
    }

    /**
     * Gets an array of all currencies assigned to donations, grouped and ordered by amount
     */
    public static function currencyDistribution(Carbon $untilDate = null): array
    {
        return self::query()
            ->select('currency')
            ->selectRaw('SUM(amount) as currencies_sum')
            ->groupBy('currency')
            ->whereNotNull('currency')
            ->createdUntil($untilDate)
            ->orderBy('currencies_sum', 'desc')
            ->pluck('currencies_sum', 'currency')
            ->map(fn ($value) => floatval($value))
            ->toArray();
    }
}
