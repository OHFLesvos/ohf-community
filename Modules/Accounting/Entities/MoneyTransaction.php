<?php

namespace Modules\Accounting\Entities;

use Modules\Accounting\Entities\SignedMoneyTransaction;
use Modules\Accounting\Support\Webling\Entities\Entrygroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use OwenIt\Auditing\Contracts\Auditable;

use Modules\Accounting\Support\Webling\Exceptions\ConnectionException;

use Carbon\Carbon;

use Gumlet\ImageResize;

class MoneyTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    const RECEIPT_PICTURE_PATH = 'public/accounting/receipts';

    public static function boot()
    {
        static::creating(function ($model) {
            $model->receipt_no = self::getNextFreeReceiptNo();
        });

        static::deleting(function($model) {
            $model->deleteReceiptPictures();
        });

        parent::boot();
    }

    protected $casts = [
        'booked' => 'boolean',
        'receipt_pictures' => 'array',
    ];

    /**
     * Gets the amount of the wallet
     * 
     * @param \Carbon\Carbon $date optional end-date until which transactions should be considered
     */
    public static function currentWallet(Carbon $date = null): ?float
    {
        $qry = SignedMoneyTransaction::selectRaw('SUM(amount) as sum');

        self::dateFilter($qry, null, $date);

        return optional($qry->first())->sum;
    }

    public static function revenueByField(string $field, Carbon $dateFrom = null, Carbon $dateTo = null): Collection
    {
        $qry = SignedMoneyTransaction::select($field)
            ->selectRaw('SUM(amount) as sum')
            ->groupBy($field)
            ->orderBy($field);

        self::dateFilter($qry, $dateFrom, $dateTo);

        return $qry->get()
            ->map(function($e) use ($field) {
                return [
                    'name'   => $e->$field,
                    'amount' => $e->sum,
                ];
            });
    }

    public static function totalSpending(Carbon $dateFrom = null, Carbon $dateTo = null): ?float
    {
        $qry = MoneyTransaction::selectRaw('SUM(amount) as sum')
            ->where('type', 'spending');
        
        self::dateFilter($qry, $dateFrom, $dateTo);
        
        return optional($qry->first())->sum;
    }

    public static function totalIncome(Carbon $dateFrom = null, Carbon $dateTo = null): ?float
    {
        $qry = MoneyTransaction::selectRaw('SUM(amount) as sum')
            ->where('type', 'income');
        
        self::dateFilter($qry, $dateFrom, $dateTo);
        
        return optional($qry->first())->sum;
    }

    private static function dateFilter($qry, Carbon $dateFrom = null, Carbon $dateTo = null)
    {
        if ($dateFrom != null) {
            $qry->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo != null) {
            $qry->whereDate('date', '<=', $dateTo);
        }
    }

    public static function getNextFreeReceiptNo()
    {
        return optional(MoneyTransaction::selectRaw('MAX(receipt_no) as val')
            ->first())
            ->val + 1;
    }

    public function getExternalUrlAttribute()
    {
        if ($this->external_id != null)
        {
            try {
                return optional(Entrygroup::find($this->external_id))->url();
            } catch (ConnectionException $ignored) { }
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
            foreach($this->receipt_pictures as $file) {
                Storage::delete($file);
            }
        }
        $this->receipt_pictures = [];
    }
}
