<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreCategory;
use Illuminate\Http\Request;
use App\Http\Resources\Accounting\Category as CategoryResource;
use App\Http\Resources\Fundraising\Donation as DonationResource;
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
                    'description',
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
        if ($request->parent_id != null) {
            $category->parent()->associate($request->parent_id);
        } else {
            $category->parent()->disassociate();
        }
        $category->save();

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load('donations'));
    }

    public function update(StoreCategory $request, Category $category)
    {
        $category->fill($request->all());
        if ($request->parent_id != null) {
            $category->parent()->associate($request->parent_id);
        } else {
            $category->parent()->disassociate();
        }
        $category->save();

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function tree(Request $request)
    {
        $request->validate([
            'exclude' => [
                'nullable',
                'int',
            ],
        ]);

        return $this->addCanUpdate(Category::queryByParent(null, $request->input('exclude')));
    }

    private function addCanUpdate($items)
    {
        return $items->map(function ($e) {
            $e['can_update'] = request()->user()->can('update', $e);
            $e['children'] = $this->addCanUpdate($e['children']);
            return $e;
        });
    }

    public function donations(Category $category)
    {
        return DonationResource::collection($category->donations()
            ->with('donor')
            ->orderBy('created_at', 'desc')
            ->paginate(10));
    }
}
