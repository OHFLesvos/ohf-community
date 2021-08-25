<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Accounting\StoreTransaction;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Setting;

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

    public function store(Wallet $wallet, StoreTransaction $request)
    {
        $this->authorize('create', Transaction::class);
        $this->authorize('view', $wallet);

        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->fees = $request->fees;
        $transaction->attendee = $request->attendee;
        $transaction->category()->associate($request->category_id);
        if (self::useSecondaryCategories()) {
            $transaction->secondary_category = $request->secondary_category;
        }
        $transaction->project()->associate($request->project_id);
        if (self::useLocations()) {
            $transaction->location = $request->location;
        }
        if (self::useCostCenters()) {
            $transaction->cost_center = $request->cost_center;
        }
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;

        $transaction->supplier()->associate($request->input('supplier_id'));

        $transaction->wallet()->associate($wallet);

        $transaction->save();

        return response()->json([
            'id' => $transaction->id,
        ], Response::HTTP_CREATED);
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return new TransactionResource($transaction->load('supplier'));
    }

    public function update(StoreTransaction $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->date = $request->date;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->fees = $request->fees;
        $transaction->attendee = $request->attendee;
        $transaction->category()->associate($request->category_id);
        if (self::useSecondaryCategories()) {
            $transaction->secondary_category = $request->secondary_category;
        }
        $transaction->project()->associate($request->project_id);
        if (self::useLocations()) {
            $transaction->location = $request->location;
        }
        if (self::useCostCenters()) {
            $transaction->cost_center = $request->cost_center;
        }
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;

        $transaction->supplier()->associate($request->input('supplier_id'));

        $transaction->save();

        return response(null, Response::HTTP_NO_CONTENT);
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

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

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

    public function taxonomies()
    {
        return response()->json([
            'secondary_categories' => Setting::get('accounting.transactions.use_secondary_categories') ? Transaction::secondaryCategories() : [],
            'locations' => Setting::get('accounting.transactions.use_locations') ? Transaction::locations() : [],
            'cost_centers' => Setting::get('accounting.transactions.use_cost_centers') ? Transaction::costCenters() : [],
            'attendees' => Transaction::attendees(),
        ]);
    }

    private static function useSecondaryCategories(): bool
    {
        return Setting::get('accounting.transactions.use_secondary_categories') ?? false;
    }

    private static function useLocations(): bool
    {
        return Setting::get('accounting.transactions.use_locations') ?? false;
    }

    private static function useCostCenters(): bool
    {
        return Setting::get('accounting.transactions.use_cost_centers') ?? false;
    }
}
