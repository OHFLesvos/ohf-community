<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionsController extends Controller
{
    use ValidatesResourceIndex;

    public function index(Wallet $wallet, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $this->authorize('view', $wallet);

        $this->validateFilter();
        $this->validatePagination();
        $this->validateSorting([
            'date',
            'created_at',
            'category',
            'secondary_category',
            'project',
            'location',
            'cost_center',
            'attendee',
            'receipt_no',
        ]);

        $advanced_filter = $this->parseAdvancedFilter($request);

        $transactions = Transaction::query()
            ->forWallet($wallet)
            ->when($request->filled('filter'), fn ($qry) => $qry->forFilter($request->input('filter')))
            ->when(count($advanced_filter) > 0, fn ($qry) => $qry->forAdvancedFilter($advanced_filter))
            ->orderBy($this->getSortBy('created_at'), $this->getSortDirection('desc'))
            ->orderBy('created_at', 'desc')
            ->with('supplier')
            ->paginate($this->getPageSize(25));

        return new TransactionCollection($transactions);
    }

    private function parseAdvancedFilter(Request $request): array
    {
        $advanced_filter = [];
        foreach (Transaction::ADVANCED_FILTER_COLUMNS as $col) {
            if (!empty($request->advanced_filter[$col])) {
                $advanced_filter[$col] = $request->advanced_filter[$col];
            }
        }
        if (!empty($request->advanced_filter['date_start'])) {
            $advanced_filter['date_start'] = $request->advanced_filter['date_start'];
        }
        if (!empty($request->advanced_filter['date_end'])) {
            $advanced_filter['date_end'] = $request->advanced_filter['date_end'];
        }

        return $advanced_filter;
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

    public function secondaryCategories()
    {
        return Transaction::secondaryCategories();
    }

    public function locations()
    {
        return Transaction::locations();
    }

    public function costCenters()
    {
        return Transaction::costCenters();
    }

    public function attendees()
    {
        return Transaction::attendees();
    }
}
