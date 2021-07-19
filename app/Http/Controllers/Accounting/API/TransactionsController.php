<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return new TransactionResource($transaction->load('supplier'));
    }

    public function updateReceipt(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'img' => [
                'array',
            ],
            'img.*' => [
                'file',
                'mimetypes:image/*,application/pdf',
            ],
        ]);

        $transaction->deleteReceiptPictures();
        for ($i = 0; $i < count($request->img); $i++) {
            $transaction->addReceiptPicture($request->file('img')[$i]);
        }
        $transaction->save();

        return response(null, 204);
    }

    public function locations()
    {
        return Transaction::locations();
    }
}
