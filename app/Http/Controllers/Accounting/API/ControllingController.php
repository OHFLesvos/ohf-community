<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use Illuminate\Http\Request;

class ControllingController extends Controller
{
    public function controlled(MoneyTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'controlled_at' => $transaction->controlled_at,
            'controlled_by' => $transaction->controlled_by,
        ]);
    }

    public function markControlled(Request $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->controlled_at = now();
        $transaction->controlled_by = $request->user()->id;
        $transaction->save();

        return response(null, 204);
    }
}
