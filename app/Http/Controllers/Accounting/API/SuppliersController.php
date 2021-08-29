<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\SuppliersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreSupplier;
use App\Models\Accounting\Supplier;
use App\Models\Accounting\Transaction;
use App\Http\Resources\Accounting\Supplier as SupplierResource;
use App\Http\Resources\Accounting\Transaction as TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Supplier::class);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Supplier::class);

        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'name',
                    'category',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 25);
        $filter = trim($request->input('filter', ''));

        return SupplierResource::collection(Supplier::query()
            ->when($filter != '', fn ($q) => $q->forFilter($filter))
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize));
    }

    public function store(StoreSupplier $request)
    {
        $supplier = new Supplier();
        $supplier->fill($request->all());
        $supplier->save();

        return new SupplierResource($supplier);
    }

    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    public function update(StoreSupplier $request, Supplier $supplier)
    {
        $supplier->fill($request->all());
        $supplier->save();

        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function transactions(Supplier $supplier, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'date',
                    'receipt_no',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'created_at');
        $sortDirection = $request->input('sortDirection', 'desc');
        $pageSize = $request->input('pageSize', 25);
        $filter = trim($request->input('filter', ''));

        return TransactionResource::collection($supplier->transactions()
            ->orderBy($sortBy, $sortDirection)
            ->forFilter($filter)
            ->paginate($pageSize));
    }

    public function export()
    {
        $this->authorize('viewAny', Supplier::class);

        $file_name = __('Suppliers') . ' - ' . now()->toDateString();
        $extension = 'xlsx';
        return (new SuppliersExport())->download($file_name . '.' . $extension);
    }

    public function names()
    {
        return Supplier::select('id', 'name', 'category')->orderBy('name')->get();
    }
}
