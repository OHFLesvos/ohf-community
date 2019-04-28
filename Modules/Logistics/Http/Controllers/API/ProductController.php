<?php

namespace Modules\Logistics\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use Modules\Logistics\Entities\Product;
use Modules\Logistics\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    public function filter(Request $request) {
        $qry = Product::limit(10)
            ->orderBy('name');
        if (isset($request->query()['query'])) {
            $qry->where('name', 'LIKE', '%' . $request->query()['query'] . '%');
        }
        return response()->json([
            "suggestions" => $qry->get()
                ->map(function($e){ 
                    return [
                        'value' => $e->name . ' ('. $e->category . ')',
                        'data' => $e->id,
                    ]; 
                })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CreateProductRequest $request
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = new Product();
        $product->fill($request->all());
        $product->save();

        return response()->json([
            'label' => $product->name . ' ('. $product->category . ')',
            'id' => $product->id,
        ], 201);
    }

}
