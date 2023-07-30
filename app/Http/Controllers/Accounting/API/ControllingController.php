<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Models\Accounting\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ControllingController extends Controller
{
    use ValidatesResourceIndex;

    public function controllable(Request $request): JsonResource
    {
        $this->authorize('viewAny', Transaction::class);

        $this->validatePagination();
        $this->validateSorting([
            'date',
        ]);

        $request->validate([
            'wallet' => [
                'nullable',
                'exists:accounting_wallets,id',
            ],
            'from' => [
                'nullable',
                'date',
            ],
            'to' => [
                'nullable',
                'date',
            ],
        ]);

        $data = Transaction::whereNull('controlled_by')
            ->when($request->has('wallet'), fn ($qry) => $qry->where('wallet_id', $request->input('wallet')))
            ->when($request->has('from'), fn ($qry) => $qry->whereDate('date', '>=', $request->input('from')))
            ->when($request->has('to'), fn ($qry) => $qry->whereDate('date', '<=', $request->input('to')))
            ->orderBy($this->getSortBy('date'), $this->getSortDirection('asc'))
            ->with('supplier')
            ->get()
            ->filter(fn (Transaction $transaction) => $request->user()->can('control', $transaction))
            ->paginate($this->getPageSize(25));

        return TransactionResource::collection($data);
    }

    public function controlled(Transaction $transaction): JsonResponse
    {
        $this->authorize('view', $transaction);

        return response()
            ->json([
                'controlled_at' => $transaction->controlled_at,
                'controlled_by' => $transaction->controlled_by,
            ]);
    }

    public function markControlled(Request $request, Transaction $transaction): Response
    {
        $this->authorize('control', $transaction);

        $transaction->controlled_at = now();
        $transaction->controlled_by = $request->user()->id;
        $transaction->save();

        return response(null, 204);
    }

    public function undoControlled(Transaction $transaction): Response
    {
        $this->authorize('undoControlling', $transaction);

        $transaction->controlled_at = null;
        $transaction->controlled_by = null;
        $transaction->save();

        return response(null, 204);
    }
}
