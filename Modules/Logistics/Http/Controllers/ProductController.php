<?php

namespace Modules\Logistics\Http\Controllers;

use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use Modules\Logistics\Entities\Product;
use Modules\Logistics\Http\Requests\CreateProductRequest;
use Modules\Logistics\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index()
    {
        $this->authorize('list', Product::class);

        return view('logistics::products.index', [
            'products' => Product::orderBy('name')->orderBy('name_local')->paginate(100),            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        return view('logistics::products.create', [
            'categories' => Product::getCategories(),
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

        return redirect()
            ->route('logistics.products.show', $product)
            ->with('success', __('logistics::products.product_registered'));
    }

    /**
     * Show the specified resource.
     * 
     * @param Product $product
     * @return Response
    */
    public function show(Product $product)    
    {
        $this->authorize('view', $product);

        return view('logistics::products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return view('logistics::products.edit', [
            'product' => $product,
            'categories' => Product::getCategories(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Product $product
     * @param UpdateProductRequest $request
     * @return Response
     */
    public function update(Product $product, UpdateProductRequest $request)
    {
        $this->authorize('update', $product);

        $product->fill($request->all());
        $product->save();

        return redirect()
            ->route('logistics.products.show', $product)
            ->with('success', __('logistics::products.product_updated'));
     }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()
            ->route('logistics.products.index')
            ->with('success', __('logistics::products.product_deleted'));
    }

}
