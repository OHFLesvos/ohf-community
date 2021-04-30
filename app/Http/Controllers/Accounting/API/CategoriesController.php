<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreCategory;
use Illuminate\Http\Request;
use App\Http\Resources\Accounting\Category as CategoryResource;
use App\Models\Accounting\Category;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }

    public function index(Request $request)
    {
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
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 25);
        $filter = trim($request->input('filter', ''));

        return CategoryResource::collection(Category::query()
            ->orderBy($sortBy, $sortDirection)
            ->when($filter != '', fn ($q) => $q->forFilter($filter))
            ->paginate($pageSize));
    }

    public function store(StoreCategory $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(StoreCategory $request, Category $category)
    {
        $category->fill($request->all());
        $category->save();

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
