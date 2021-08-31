<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreBudget;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Accounting\TransactionCollection;
use App\Models\Accounting\Budget;
use App\Models\Accounting\Transaction;
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
        return new BudgetResource($budget);
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

    public function transactions(Budget $budget)
    {
        $this->authorize('viewAny', Transaction::class);

        return new TransactionCollection($budget->transactions);
    }
}
