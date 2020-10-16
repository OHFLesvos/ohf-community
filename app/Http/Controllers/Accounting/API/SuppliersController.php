<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\SuppliersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreSupplier;
use App\Models\Accounting\Supplier;
use App\Models\Accounting\MoneyTransaction;
use App\Http\Resources\Accounting\Supplier as SupplierResource;
use App\Http\Resources\Accounting\MoneyTransaction as MoneyTransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Supplier::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplier $request)
    {
        $supplier = new Supplier();
        $supplier->fill($request->all());
        $supplier->save();

        return new SupplierResource($supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accounting\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounting\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSupplier $request, Supplier $supplier)
    {
        $supplier->fill($request->all());
        $supplier->save();

        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accounting\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accounting\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function transactions(Supplier $supplier, Request $request)
    {
        $this->authorize('viewAny', MoneyTransaction::class);

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

        return MoneyTransactionResource::collection($supplier->transactions()
            ->orderBy($sortBy, $sortDirection)
            ->forFilter(['description' => $filter])
            ->paginate($pageSize));
    }

    public function export()
    {
        $this->authorize('viewAny', Supplier::class);

        $file_name = __('accounting.suppliers') . ' - ' . now()->toDateString();
        $extension = 'xlsx';
        return (new SuppliersExport())->download($file_name . '.' . $extension);
    }
}
