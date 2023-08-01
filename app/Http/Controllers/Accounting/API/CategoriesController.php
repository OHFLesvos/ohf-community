<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreCategory;
use App\Http\Resources\Accounting\Category as CategoryResource;
use App\Models\Accounting\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }

    public function index(Request $request): JsonResource
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
            ->when(filled($filter), fn (Builder $query) => $this->filterQuery($query, $filter))
            ->paginate($pageSize));
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where(fn (Builder $wq) => $wq
            ->where('name', 'LIKE', '%'.$filter.'%')
            ->orWhere('description', 'LIKE', '%'.$filter.'%')
        );
    }

    public function store(StoreCategory $request): JsonResource
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

    public function show(Category $category): JsonResource
    {
        return new CategoryResource($category);
    }

    public function update(StoreCategory $request, Category $category): JsonResource
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

    public function destroy(Category $category): Response
    {
        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function tree(Request $request): Collection
    {
        $request->validate([
            'exclude' => [
                'nullable',
                'int',
            ],
        ]);

        return $this->addCanUpdate(Category::queryByParent(null, $request->input('exclude')));
    }

    private function addCanUpdate(Collection $items): Collection
    {
        return $items->map(function ($e) {
            $e['can_update'] = request()->user()->can('update', $e);
            $e['children'] = $this->addCanUpdate($e['children']);

            return $e;
        });
    }
}
