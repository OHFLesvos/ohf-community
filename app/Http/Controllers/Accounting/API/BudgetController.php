<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\BudgetTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Accounting\StoreBudget;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Models\Accounting\Budget;
use App\Models\Accounting\Transaction;
use App\Models\Fundraising\Donation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use ZipStream\ZipStream;

class BudgetController extends Controller
{
    use ValidatesResourceIndex;

    public function __construct()
    {
        $this->authorizeResource(Budget::class);
    }

    public function index(Request $request): JsonResource
    {
        $this->validateFilter();
        $this->validatePagination();

        $filter = $request->input('filter', '');

        $data = Budget::query()
            ->when(filled($filter), fn (Builder $query) => $this->filterQuery($query, $filter))
            ->orderBy('name')
            ->with('transactions')
            ->paginate($this->getPageSize(10));

        return BudgetResource::collection($data);
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where(fn (Builder $qry1) => $qry1
            ->where('name', 'LIKE', '%'.$filter.'%')
            ->orWhere('description', 'LIKE', '%'.$filter.'%')
        );
    }

    public function store(StoreBudget $request): Response
    {
        $budget = new Budget();
        $budget->fill($request->all());
        $budget->save();

        return response(null, Response::HTTP_CREATED);
    }

    public function show(Budget $budget): JsonResource
    {
        return new BudgetResource($budget->load(['donor']));
    }

    public function update(StoreBudget $request, Budget $budget): Response
    {
        $budget->fill($request->all());
        $budget->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Budget $budget): Response
    {
        $budget->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function names(): Collection
    {
        return Budget::select('id', 'name', 'is_completed')
            ->orderBy('name')
            ->get();
    }

    public function transactions(Budget $budget): JsonResource
    {
        $this->authorize('viewAny', Transaction::class);

        $this->validatePagination();

        $data = $budget->transactions()
            ->orderBy('date', 'desc')
            ->with('supplier')
            ->paginate($this->getPageSize(10));

        return TransactionResource::collection($data);
    }

    public function donations(Budget $budget): JsonResource
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
                ],
            ]);
    }

    public function export(Budget $budget, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $export = new BudgetTransactionsExport($budget);

        $file_name = config('app.name').' '.__('Budget').' ['.$budget->name.'] ('.Carbon::now()->toDateString().')';
        $file_ext = 'xlsx';

        if ($request->missing('include_pictures')) {
            return $export->download($file_name.'.'.$file_ext);
        }

        $zip = new ZipStream(outputName: $file_name.'.zip', sendHttpHeaders: true);
        $temp_file = 'temp/'.uniqid().'.'.$file_ext;
        $export->store($temp_file);
        $zip->addFileFromPath($file_name.'.'.$file_ext, storage_path('app/'.$temp_file));
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
                        $id .= ' ('.($counter + 1).')';
                    }
                    $zip->addFileFromPath('receipts/'.$id.'.'.$ext, $picture_path);
                    $counter++;
                }
            }
        }
        $zip->finish();
    }
}
