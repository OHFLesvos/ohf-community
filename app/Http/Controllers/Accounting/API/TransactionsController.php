<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function undoBooking(Transaction $transaction)
    {
        $this->authorize('undoBooking', $transaction);

        if ($transaction->external_id != null && Entrygroup::find($transaction->external_id) != null) {
            return response()->json([
                'message' => __('Transaction not updated; the external record still exists and has to be deleted beforehand.')
            ], Response::HTTP_CONFLICT);
        }

        $transaction->booked = false;
        $transaction->external_id = null;
        $transaction->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function locations()
    {
        return Transaction::locations();
    }
}
