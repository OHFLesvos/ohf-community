<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunityVolunteers\CommunityVolunteer as CommunityVolunteerResource;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CommunityVolunteerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', CommunityVolunteer::class);

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
                    'first_name',
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
            'workStatus' => [
                'nullable',
                Rule::in([
                    'active',
                    'future',
                    'alumni',
                ]),
            ],
        ]);

        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $orderInDB = ! in_array($sortBy, ['age']);

        $pageSize = $request->input('pageSize', 10);
        $filter = $request->input('filter', '');
        $workStatus = $request->input('workStatus', 'active');

        $query = CommunityVolunteer::query()
            ->workStatus($workStatus)
            ->when(filled($filter), fn (Builder $qry) => $qry->forFilterTerms(split_by_whitespace(trim($filter))))
            ->with(['responsibilities:name,description']);

        if ($orderInDB) {
            $data = $query->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);
        } else {
            $data = $query->get()
                ->when($sortDirection == 'asc', fn (Collection $col) => $col->sortBy($sortBy))
                ->when($sortDirection == 'desc', fn (Collection $col) => $col->sortByDesc($sortBy))
                ->values()
                ->paginate($pageSize);
        }

        return CommunityVolunteerResource::collection($data);
    }

    public function show(CommunityVolunteer $cmtyvol)
    {
        $this->authorize('view', $cmtyvol);

        return new CommunityVolunteerResource($cmtyvol);
    }
}
