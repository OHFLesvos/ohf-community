<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\BudgetTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreBudget;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Models\Accounting\Budget;
use App\Models\Accounting\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Budget::class);
    }

    public function index(Request $request)
    {
        $data = Budget::forFilter($request->input('filter', ''))
            ->orderBy('name')
            ->with('transactions')
            ->paginate(10);

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
        return new BudgetResource($budget->load('donor'));
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

        $data = $budget->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate();

        return new TransactionCollection($data);
    }

    public function export(Budget $budget)
    {
        $this->authorize('viewAny', Transaction::class);

        $file_name = config('app.name') . ' ' . __('Budget') . ' [' . $budget->name . '] (' . Carbon::now()->toDateString() . ')';
        $file_ext = "xlsx";

        $export = new BudgetTransactionsExport($budget);
        return $export->download($file_name . '.' . $file_ext);
    }
}
