<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\BudgetTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Accounting\StoreBudget;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Models\Accounting\Budget;
use App\Models\Accounting\Transaction;
use App\Models\Fundraising\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class BudgetController extends Controller
{
    use ValidatesResourceIndex;

    public function __construct()
    {
        $this->authorizeResource(Budget::class);
    }

    public function index(Request $request)
    {
        $this->validateFilter();
        $this->validatePagination();

        $data = Budget::forFilter($request->input('filter', ''))
            ->orderBy('name')
            ->with('transactions')
            ->paginate($this->getPageSize(10));

        return BudgetResource::collection($data);
    }

    public function store(StoreBudget $request)
    {
        $budget = new Budget();
        $budget->fill($request->all());
        $budget->save();

        return response(null, Response::HTTP_CREATED);
    }

    public function show(Budget $budget)
    {
        return new BudgetResource($budget->load(['donor']));
    }

    public function showPublic(Budget $budget)
    {
        return new BudgetResource($budget->load(['donor']));
    }

    public function update(StoreBudget $request, Budget $budget)
    {
        $budget->fill($request->all());
        $budget->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function names()
    {
        return Budget::select('id', 'name', 'is_completed')
            ->orderBy('name')
            ->get();
    }

    public function transactions(Budget $budget)
    {
        $this->authorize('viewAny', Transaction::class);

        $this->validatePagination();

        $data = $budget->transactions()
            ->orderBy('date', 'desc')
            ->with('supplier')
            ->paginate($this->getPageSize(10));

        return new TransactionCollection($data);
    }

    public function donations(Budget $budget)
    {
        $this->authorize('viewAny', Donation::class);

        $this->validatePagination();

        $data = $budget->donations()
            ->with(['donor'])
            ->orderBy('date', 'desc')
            ->paginate($this->getPageSize(10));

        return (new DonationCollection($data))
            ->additional([
                'meta' => [
                    'total_exchange_amount' => $budget->donations()->sum('exchange_amount'),
                ]
            ]);
    }

    public function export(Budget $budget, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $export = new BudgetTransactionsExport($budget);

        $file_name = config('app.name') . ' ' . __('Budget') . ' [' . $budget->name . '] (' . Carbon::now()->toDateString() . ')';
        $file_ext = "xlsx";

        if ($request->has('include_pictures')) {
            $options = new Archive();
            $options->setSendHttpHeaders(true);
            $zip = new ZipStream($file_name . '.zip', $options);
            $temp_file = 'temp/' . uniqid() . '.' . $file_ext;
            $export->store($temp_file);
            $zip->addFileFromPath($file_name . '.' . $file_ext, storage_path('app/' . $temp_file));
            Storage::delete($temp_file);
            foreach ($budget->transactions as $transaction) {
                if (empty($transaction->receipt_pictures)) {
                    continue;
                }
                $counter = 0;
                foreach (collect($transaction->receipt_pictures)->filter(fn ($picture) => Storage::exists($picture)) as $picture) {
                    $picture_path = storage_path('app/'.$picture);
                    if (is_file($picture_path)) {
                        $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                        $id = (string) $transaction->receipt_no;
                        if ($counter > 0) {
                            $id .= " (" . ($counter + 1) . ')';
                        }
                        $zip->addFileFromPath('receipts/' . $id . '.' . $ext, $picture_path);
                        $counter++;
                    }
                }
            }
            $zip->finish();
            return;
        }

        return $export->download($file_name . '.' . $file_ext);
    }
}
