<?php

namespace App\Http\Controllers\Helpers\API;

use App\Http\Controllers\Controller;
use App\Models\Helpers\Helper;
use App\Http\Resources\Helpers\Helper as HelperResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class HelperController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Helper::class);

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
                    'family_name',
                    'nationality',
                    'age',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
            'scope' => [
                'nullable',
                Rule::in([
                    'active',
                    'future',
                    'alumni',
                ]),
            ],
        ]);

        $sortBy = $request->input('sortBy', 'person.name');
        $sortMap = collect([
            'name' => 'person.name',
            'family_name' => 'person.family_name',
            'nationality' => 'person.nationality',
            'age' =>'person.age',
        ]);
        $sortBy = $sortMap->get($sortBy, $sortBy);
        $sortDirection = $request->input('sortDirection', 'asc');

        $pageSize = $request->input('pageSize', 10);
        $filter = $request->input('filter', '');
        $scopeMethod = $request->input('scope', 'active');

        $data = Helper::query()
            ->$scopeMethod()
            ->when(filled($filter), fn (Builder $query) => $query->forFilter(trim($filter)))
            ->with(['responsibilities:name'])
            ->get()
            ->when($sortDirection == 'asc', fn (Collection $col) => $col->sortBy($sortBy))
            ->when($sortDirection == 'desc', fn (Collection $col) => $col->sortByDesc($sortBy))
            ->values()
            ->paginate($pageSize);

        return HelperResource::collection($data);
    }

    public function show(Helper $helper)
    {
        $this->authorize('view', $helper);

        return new HelperResource($helper);
    }
}
