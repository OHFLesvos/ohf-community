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
use Illuminate\Support\Str;
use Org_Heigl\Ghostscript\Ghostscript;
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
                } elseif ($col == 'attendee' || $col == 'description') {
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
        $storedFile = $file->store(self::RECEIPT_PICTURE_PATH);
        $storedFilePath = Storage::path($storedFile);
        $thumbSize = config('accounting.thumbnail_size');
        $maxImageDimension = config('accounting.max_image_size');

        if (Str::startsWith($file->getMimeType(), 'image/')) {
            self::createThumbnail($storedFilePath, $thumbSize);
            self::resizeImage($storedFilePath, $maxImageDimension);
        } elseif ($file->getMimeType() == 'application/pdf') {
            self::createPdfThumbnail($storedFilePath, $thumbSize);
        }

        $pictures = $this->receipt_pictures ?? [];
        $pictures[] = $storedFile;
        $this->receipt_pictures = $pictures;
    }

    private static function createThumbnail($path, $dimensions)
    {
        $thumbPath = thumb_path($path);
        $image = new ImageResize($path);
        $image->crop($dimensions, $dimensions);
        $image->save($thumbPath);
    }

    private static function resizeImage($path, $dimensions)
    {
        $image = new ImageResize($path);
        $image->resizeToBestFit($dimensions, $dimensions);
        $image->save($path);
    }

    private static function createPdfThumbnail($path, $dimensions)
    {
        $thumbPath = thumb_path($path, "jpeg");
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            Ghostscript::setGsPath("C:\Program Files\gs\gs9.52\bin\gswin64c.exe");
        }
        $pdf = new \Spatie\PdfToImage\Pdf($path);
        $pdf->saveImage($thumbPath);
        $image = new ImageResize($thumbPath);
        $image->crop($dimensions, $dimensions);
        $image->save($thumbPath);
    }

    public function deleteReceiptPicture(string $picture)
    {
        Storage::delete($picture);
        Storage::delete(thumb_path($picture));
        Storage::delete(thumb_path($picture, 'jpeg'));

        if (!empty($this->receipt_pictures)) {
            $this->receipt_pictures = collect($this->receipt_pictures)
                ->reject(fn ($e) => $e == $picture)
                ->values()
                ->toArray();
        }
    }

    public function deleteReceiptPictures()
    {
        if (!empty($this->receipt_pictures)) {
            foreach ($this->receipt_pictures as $file) {
                Storage::delete($file);
                Storage::delete(thumb_path($file));
                Storage::delete(thumb_path($file, 'jpeg'));
            }
        }
        $this->receipt_pictures = [];
    }

    public static function attendees(): array
    {
        return self::select('attendee')
            ->whereNotNull('attendee')
            ->distinct()
            ->orderBy('attendee')
            ->get()
            ->pluck('attendee')
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
