<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use Illuminate\Http\Request;

class ControllingController extends Controller
{
    public function controllable(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $request->validate([
            'wallet' => [
                'nullable',
                'exists:accounting_wallets,id',
            ],
            'from' => [
                'nullable',
                'date'
            ],
            'to' => [
                'nullable',
                'date'
            ],
        ]);

        $data = Transaction::whereNull('controlled_by')
            ->when($request->has('wallet'), fn ($qry) => $qry->where('wallet_id', $request->input('wallet')))
            ->when($request->has('from'), fn ($qry) => $qry->whereDate('from', '>=', $request->input('from')))
            ->when($request->has('to'), fn ($qry) => $qry->whereDate('to', '<=', $request->input('to')))
            ->orderBy('date', 'asc')
            ->get()
            ->filter(fn(Transaction $transaction) => $request->user()->can('control', $transaction))
            ->paginate();

        return TransactionResource::collection($data);
    }

    public function controlled(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'controlled_at' => $transaction->controlled_at,
            'controlled_by' => $transaction->controlled_by,
        ]);
    }

    public function markControlled(Request $request, Transaction $transaction)
    {
        $this->authorize('control', $transaction);

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
