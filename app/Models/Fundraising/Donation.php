<?php

namespace App\Models\Fundraising;

use App\Models\Accounting\Category;
use App\Models\Traits\CreatedUntilScope;
use App\Models\Traits\InDateRangeScope;
use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    use NullableFields;
    use InDateRangeScope;
    use CreatedUntilScope;

    protected $nullable = [
        'purpose',
        'reference',
        'in_name_of',
    ];

    protected $dates = [
        'deleted_at',
        'thanked',
    ];

    protected $casts = [
        'amount' => 'float',
        'exchange_amount' => 'float',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function accountingCategory()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include donations from the given year, if specified.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForYear($query, ?int $year)
    {
        if ($year !== null) {
            $query->whereYear('date', $year);
        }
        return $query;
    }

    /**
     * Scope a query to only include donations matching the given filter
     * If no filter is specified, all records will be returned.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string|null $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, ?string $filter = '')
    {
        if (! empty($filter)) {
            $query->where(function ($wq) use ($filter) {
                return $wq->where('date', $filter)
                    ->orWhere('amount', $filter)
                    ->orWhereHas('donor', fn ($query) => $query->forSimpleFilter($filter))
                    ->orWhere('channel', 'LIKE', '%' . $filter . '%')
                    ->orWhere('purpose', 'LIKE', '%' . $filter . '%')
                    ->orWhere('reference', $filter)
                    ->orWhere('in_name_of', 'LIKE', '%' . $filter . '%');
            });
        }
        return $query;
    }

    /**
     * Gets a sorted list of all channels registered for donations
     *
     * @return array
     */
    public static function channels(): array
    {
        return self::select('channel')
            ->distinct()
            ->orderBy('channel')
            ->get()
            ->pluck('channel')
            ->toArray();
    }

    /**
     * Gets an array of all currencies assigned to donations, grouped and ordered by amount
     *
     * @param string|\Carbon\Carbon|null $untilDate
     * @return array
     */
    public static function currencyDistribution($untilDate = null): array
    {
        return self::select('currency')
            ->selectRaw('SUM(amount) as currencies_sum')
            ->groupBy('currency')
            ->whereNotNull('currency')
            ->createdUntil($untilDate)
            ->orderBy('currencies_sum', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [ $e->currency => floatVal($e->currencies_sum) ])
            ->toArray();
    }

    /**
     * Gets an array of all channels assigned to donations, grouped and ordered by amount
     *
     * @param string|\Carbon\Carbon|null $untilDate
     * @return array
     */
    public static function channelDistribution($untilDate = null): array
    {
        return self::select('channel')
            ->selectRaw('COUNT(*) as channels_count')
            ->groupBy('channel')
            ->whereNotNull('channel')
            ->createdUntil($untilDate)
            ->orderBy('channels_count', 'desc')
            ->get()
            ->mapWithKeys(fn ($e) => [ $e->channel => floatVal($e->channels_count) ])
            ->toArray();
    }
}
