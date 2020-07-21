<?php

namespace App\Models\Accounting;

use App\Support\Accounting\Webling\Entities\Entrygroup;
use App\Support\Accounting\Webling\Exceptions\ConnectionException;
use App\User;
use Carbon\Carbon;
use Gumlet\ImageResize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class MoneyTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    private const RECEIPT_PICTURE_PATH = 'public/accounting/receipts';

    public static function boot()
    {
        static::deleting(function ($model) {
            $model->deleteReceiptPictures();
        });

        parent::boot();
    }

    protected $casts = [
        'booked' => 'boolean',
        'receipt_pictures' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'controlled_at',
    ];

    /**
     * Get the wallet that owns this transaction.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the wallet that owns this transaction.
     */
    public function controller()
    {
        return $this->belongsTo(User::class, 'controlled_by');
    }

    /**
     * Scope a query to only include transactions from a given date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|Carbon|null  $dateFrom
     * @param  string|Carbon|null  $dateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDateRange($query, $dateFrom = null, $dateTo = null)
    {
        if ($dateFrom !== null) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo !== null) {
            $query->whereDate('date', '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Scope a query to only include transactions for the given wallet
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Wallet $wallet
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForWallet($query, Wallet $wallet)
    {
        return $query->where('wallet_id', $wallet->id);
    }

    /**
     * Scope a query to only include transactions matching the given filter conditions
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param array $filter
     * @param bool|null $skipDates
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilter($query, array $filter, ?bool $skipDates = false)
    {
        foreach (config('accounting.filter_columns') as $col) {
            if (!empty($filter[$col])) {
                if ($col == 'today') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($col == 'no_receipt') {
                    $query->where(function ($query) {
                        $query->whereNull('receipt_no');
                        $query->orWhereNull('receipt_pictures');
                        $query->orWhere('receipt_pictures', '[]');
                    });
                } elseif ($col == 'beneficiary' || $col == 'description') {
                    $query->where($col, 'like', '%' . $filter[$col] . '%');
                } else {
                    $query->where($col, $filter[$col]);
                }
            }
        }
        if (!$skipDates) {
            if (!empty($filter['date_start'])) {
                $query->whereDate('date', '>=', $filter['date_start']);
            }
            if (!empty($filter['date_end'])) {
                $query->whereDate('date', '<=', $filter['date_end']);
            }
        }
        return $query;
    }

    /**
     * Scope a query to only include transactions which have not been booked
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotBooked($query)
    {
        return $query->where('booked', false);
    }

    public function getExternalUrlAttribute()
    {
        if ($this->external_id != null) {
            try {
                return optional(Entrygroup::find($this->external_id))->url();
            } catch (ConnectionException $e) {
                Log::warning('Unable to get external URL: ' . $e->getMessage());
            }
        }
        return null;
    }

    public function addReceiptPicture($file)
    {
        // Resize image
        $image = new ImageResize($file->getRealPath());
        $image->resizeToBestFit(1024, 1024);
        $image->save($file->getRealPath());

        $pictures = $this->receipt_pictures ?? [];
        $pictures[] = $file->store(self::RECEIPT_PICTURE_PATH);
        $this->receipt_pictures = $pictures;
    }

    public function deleteReceiptPictures()
    {
        if (!empty($this->receipt_pictures)) {
            foreach ($this->receipt_pictures as $file) {
                Storage::delete($file);
            }
        }
        $this->receipt_pictures = [];
    }

    public static function beneficiaries(): array
    {
        return self::select('beneficiary')
            ->whereNotNull('beneficiary')
            ->distinct()
            ->orderBy('beneficiary')
            ->get()
            ->pluck('beneficiary')
            ->toArray();
    }

    public static function categories(): array
    {
        return self::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->pluck('category')
            ->toArray();
    }

    public static function secondaryCategories(): array
    {
        return self::select('secondary_category')
            ->whereNotNull('secondary_category')
            ->distinct()
            ->orderBy('secondary_category')
            ->get()
            ->pluck('secondary_category')
            ->toArray();
    }

    public static function projects(): array
    {
        return self::select('project')
            ->whereNotNull('project')
            ->distinct()
            ->orderBy('project')
            ->get()
            ->pluck('project')
            ->toArray();
    }

    public static function locations(): array
    {
        return self::select('location')
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->get()
            ->pluck('location')
            ->toArray();
    }

    public static function costCenters(): array
    {
        return self::select('cost_center')
            ->whereNotNull('cost_center')
            ->distinct()
            ->orderBy('cost_center')
            ->get()
            ->pluck('cost_center')
            ->toArray();
    }
}
