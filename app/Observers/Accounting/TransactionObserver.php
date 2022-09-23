<?php

namespace App\Observers\Accounting;

use App\Models\Accounting\Transaction;
use App\Support\Accounting\ReceiptPictureUtil;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    public function updated(Transaction $transaction): void
    {
        $removed_pictures = array_diff(
            $transaction->getOriginal('receipt_pictures', []) ?? [],
            $transaction->receipt_pictures ?? []
        );
        ReceiptPictureUtil::deleteReceiptPictures($removed_pictures);
    }

    public function deleted(Transaction $transaction): void
    {
        Log::info('Deleted accounting transaction', [
            'transaction_id' => $transaction->id,
            'transaction_date' => $transaction->date,
            'transaction_type' => $transaction->type,
            'transaction_amount' => $transaction->amount,
            'transaction_wallet_id' => $transaction->wallet_id,
            'client_ip' => request()?->ip(),
        ]);

        ReceiptPictureUtil::deleteReceiptPictures($transaction->receipt_pictures);
    }
}
