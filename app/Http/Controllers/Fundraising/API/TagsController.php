<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag as TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $this->authorize('viewAny', Tag::class);

        return TagResource::collection(Tag::has('donors')
            ->when($request->filled('filter'), fn (Builder $query) => $this->filterQuery($query, $request->input('filter')))
            ->orderBy('name')
            ->get());
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where('name', 'LIKE', '%'.$filter.'%');
    }
}
