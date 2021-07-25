<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Setting;

class TransactionsController extends Controller
{
    public function index(Wallet $wallet, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $this->authorize('view', $wallet);

        $request->validate([
            'date_start' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'date_end' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'type' => [
                'nullable',
                Rule::in(['income', 'spending']),
            ],
            'month' => [
                'nullable',
                'regex:/[0-1]?[1-9]/'
            ],
            'year' => [
                'nullable',
                'integer',
                'min:2017',
                'max:' . Carbon::today()->year
            ],
            'sortBy' => [
                'nullable',
                Rule::in([
                    'date',
                    'created_at',
                    'category',
                    'secondary_category',
                    'project',
                    'location',
                    'cost_center',
                    'attendee',
                    'receipt_no'
                ])
            ],
            'sortDirection' => [
                'nullable',
                Rule::in(['asc', 'desc'])
            ],
        ]);

        $sortBy = 'created_at';
        $sortDirection = 'desc';
        if (isset($request->sortBy)) {
            $sortBy = $request->sortBy;
        }
        if (isset($request->sortDirection)) {
            $sortDirection = $request->sortDirection;
        }

        // $filter = [];
        // foreach (config('accounting.filter_columns') as $col) {
        //     if (!empty($request->filter[$col])) {
        //         $filter[$col] = $request->filter[$col];
        //     } elseif (isset($request->filter)) {
        //         unset($filter[$col]);
        //     }
        // }
        // if (!empty($request->filter['date_start'])) {
        //     $filter['date_start'] = $request->filter['date_start'];
        // } elseif (isset($request->filter)) {
        //     unset($filter['date_start']);
        // }
        // if (!empty($request->filter['date_end'])) {
        //     $filter['date_end'] = $request->filter['date_end'];
        // } elseif (isset($request->filter)) {
        //     unset($filter['date_end']);
        // }

        $transactions = Transaction::query()
            ->forWallet($wallet)
            ->when($request->filled('filter'), fn($qry) => $qry->forFilter($request->input('filter')))
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('created_at', 'DESC')
            ->with('supplier')
            ->paginate(25);

        return new TransactionCollection($transactions);
    }

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

        return response()->json($transaction->receiptPictureArray());
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
