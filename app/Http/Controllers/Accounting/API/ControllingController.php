<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreControlled;
use App\Models\Accounting\Transaction;

class ControllingController extends Controller
{
    public function controlled(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'controlled_at' => $transaction->controlled_at,
            'controlled_by' => $transaction->controlled_by,
        ]);
    }

    public function markControlled(StoreControlled $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->controlled_at = now();
        $transaction->controlled_by = $request->user()->id;
        $transaction->save();

        return response(null, 204);
    }

    public function undoControlled(Transaction $transaction)
    {
        $this->authorize('undoControlling', $transaction);

        $transaction->controlled_at = null;
        $transaction->controlled_by = null;
        $transaction->save();

        return response(null, 204);
    }
}
