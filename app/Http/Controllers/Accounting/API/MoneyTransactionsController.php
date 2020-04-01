<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use Illuminate\Http\Request;

class MoneyTransactionsController extends Controller
{
    public function updateReceipt(Request $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->deleteReceiptPictures(); // TODO no need to clear pictures for multi picture support
        $transaction->addReceiptPicture($request->file('img'));
        $transaction->save();

        return response(null, 204);
    }
}
