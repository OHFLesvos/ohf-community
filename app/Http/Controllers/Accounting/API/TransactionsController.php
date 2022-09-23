<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AuthorizeAny;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Accounting\StoreTransaction;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Http\Resources\Accounting\TransactionHistory;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\ReceiptPictureUtil;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Models\Audit;
use Setting;

class TransactionsController extends Controller
{
    use ValidatesResourceIndex;
    use AuthorizeAny;

    public function index(Request $request): JsonResource
    {
        $this->authorize('viewAny', Transaction::class);

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
        $request->validate([
            'wallet' => [
                'nullable',
                'exists:accounting_wallets,id',
            ],
            'advanced_filter' => [
                'nullable',
                'array',
            ],
        ]);

        $advanced_filter = $this->parseAdvancedFilter($request);

        $allowedWalletIds = Wallet::all()
            ->filter(fn (Wallet $wallet) => $request->user()->can('view', $wallet))
            ->pluck('id');

        $appends = [];
        if ($request->filled('wallet')) {
            $appends['wallet'] = $request->input('wallet');
        }

        $transactions = Transaction::query()
            ->whereIn('wallet_id', $allowedWalletIds)
            ->when($request->filled('wallet'), fn ($qry) => $qry->where('wallet_id', $request->input('wallet')))
            ->when($request->filled('filter'), fn ($qry) => $qry->forFilter($request->input('filter')))
            ->when(count($advanced_filter) > 0, fn ($qry) => $qry->forAdvancedFilter($advanced_filter))
            ->when(
                ! empty($request->advanced_filter['date_start']),
                fn ($qry) => $qry->whereDate('date', '>=', $request->advanced_filter['date_start'])
            )
            ->when(
                ! empty($request->advanced_filter['date_end']),
                fn ($qry) => $qry->whereDate('date', '<=', $request->advanced_filter['date_end'])
            )
            ->orderBy($this->getSortBy('created_at'), $this->getSortDirection('desc'))
            ->orderBy('created_at', 'desc')
            ->with('supplier')
            ->paginate($this->getPageSize(25))
            ->appends($appends);

        return TransactionResource::collection($transactions);
    }

    private function parseAdvancedFilter(Request $request): array
    {
        $advanced_filter = [];
        foreach (Transaction::ADVANCED_FILTER_COLUMNS as $col) {
            if (! empty($request->advanced_filter[$col])) {
                $advanced_filter[$col] = $request->advanced_filter[$col];
            }
        }

        return $advanced_filter;
    }

    public function history(Request $request): JsonResource
    {
        $this->authorize('viewAny', Transaction::class);

        $this->validatePagination();
        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);

        return TransactionHistory::collection(Audit::where('auditable_type', Transaction::class)
            ->when($request->has('date'), fn ($qry) => $qry->whereDate('created_at', $request->input('date')))
            ->orderBy('created_at', 'desc')
            ->paginate(10));
    }

    public function store(StoreTransaction $request): JsonResponse
    {
        $this->authorize('create', Transaction::class);

        $wallet = Wallet::findOrFail($request->wallet);
        $this->authorize('view', $wallet);

        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->receipt_no = $request->receipt_no ?? $wallet->getNextFreeReceiptNumber();
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
        $transaction->budget()->associate($request->input('budget_id'));

        $transaction->wallet()->associate($wallet);

        $transaction->save();

        return response()
            ->json([
                'id' => $transaction->id,
            ], Response::HTTP_CREATED);
    }

    public function show(Transaction $transaction): JsonResource
    {
        $this->authorize('view', $transaction);

        return new TransactionResource($transaction->load('supplier'));
    }

    public function transactionHistory(Transaction $transaction): JsonResource
    {
        $this->authorize('view', $transaction);

        return TransactionHistory::collection($transaction->audits()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get());
    }

    public function update(StoreTransaction $request, Transaction $transaction): Response
    {
        $this->authorizeAny(['update', 'updateMetadata'], $transaction);

        $canUpdate = $request->user()->can('update', $transaction);
        $canUpdateMetadata = $request->user()->can('updateMetadata', $transaction);

        if ($canUpdate) {
            $transaction->date = $request->date;
            $transaction->receipt_no = $request->receipt_no;
            $transaction->type = $request->type;
            $transaction->amount = $request->amount;
            $transaction->fees = $request->fees;
        }

        if ($canUpdate || $canUpdateMetadata) {
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
        }

        if ($canUpdate) {
            $transaction->budget()->associate($request->input('budget_id'));

            foreach ($request->input('delete_receipts', []) as $picture) {
                $transaction->deleteReceiptPicture($picture);
            }
        }

        $transaction->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateReceipt(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('updateReceipt', $transaction);

        $request->validate([
            'img' => [
                'array',
            ],
            'img.*' => [
                'file',
                'mimetypes:image/*,application/pdf',
            ],
        ]);

        $pictures = $transaction->receipt_pictures ?? [];
        for ($i = 0; $i < count($request->img); $i++) {
            $pictures[] = ReceiptPictureUtil::addReceiptPicture($request->file('img')[$i]);
        }
        $transaction->receipt_pictures = $pictures;
        $transaction->save();

        return response()
            ->json($transaction->receiptPictureArray());
    }

    public function rotateReceipt(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('updateReceipt', $transaction);

        $request->validate([
            'picture' => [
                Rule::in($transaction->receipt_pictures),
            ],
            'direction' => [
                'nullable',
                Rule::in(['left', 'right']),
            ],
        ]);

        ReceiptPictureUtil::rotateReceiptPicture($request->picture, $request->input('direction', 'right'));

        return response()
            ->json($transaction->receiptPictureArray());
    }

    public function undoBooking(Transaction $transaction): Response|JsonResponse
    {
        $this->authorize('undoBooking', $transaction);

        if ($transaction->external_id != null && Entrygroup::find($transaction->external_id) != null) {
            return response()->json([
                'message' => __('Transaction not updated; the external record still exists and has to be deleted beforehand.'),
            ], Response::HTTP_CONFLICT);
        }

        $transaction->booked = false;
        $transaction->external_id = null;
        $transaction->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Transaction $transaction): Response
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function secondaryCategories(): array
    {
        return Transaction::secondaryCategories();
    }

    public function locations(): array
    {
        return Transaction::locations();
    }

    public function costCenters(): array
    {
        return Transaction::costCenters();
    }

    public function attendees(): array
    {
        return Transaction::attendees();
    }

    public function taxonomies(): JsonResponse
    {
        $this->authorize('viewAny', Transaction::class);

        return response()
            ->json([
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
