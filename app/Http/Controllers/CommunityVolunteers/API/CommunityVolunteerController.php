<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommunityVolunteers\StoreCommunityVolunteer;
use App\Http\Resources\CommunityVolunteers\CommunityVolunteer as CommunityVolunteerResource;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommunityVolunteerController extends Controller
{
    public function index(Request $request): JsonResource
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
            ->when(filled($filter), fn (Builder $qry) => $this->filterTerms($qry, split_by_whitespace(trim($filter))))
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

    private function filterTerms(Builder $query, array $terms): Builder
    {
        foreach ($terms as $term) {
            $query->where(fn (Builder $wq) => $this->filterQuery($wq, $term));
        }

        return $query;
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where(
            fn (Builder $q) => $q->whereRaw('CONCAT(first_name, \' \', family_name) LIKE ?', ['%'.$filter.'%'])
                ->orWhereRaw('CONCAT(family_name, \' \', first_name) LIKE ?', ['%'.$filter.'%'])
                ->orWhere('first_name', 'LIKE', '%'.$filter.'%')
                ->orWhere('nickname', 'LIKE', '%'.$filter.'%')
                ->orWhere('family_name', 'LIKE', '%'.$filter.'%')
                ->orWhere('date_of_birth', $filter)
                ->orWhere('nationality', 'LIKE', '%'.$filter.'%')
                ->orWhere('police_no', $filter)
                ->orWhere('languages', 'LIKE', '%'.$filter.'%')
                ->orWhereHas('responsibilities', fn (Builder $query) => $query->where('name', 'LIKE', '%'.$filter.'%'))
                ->orWhere('local_phone', 'LIKE', '%'.$filter.'%')
                ->orWhere('other_phone', 'LIKE', '%'.$filter.'%')
                ->orWhere('whatsapp', 'LIKE', '%'.$filter.'%')
                ->orWhere('email', 'LIKE', '%'.$filter.'%')
                ->orWhere('skype', 'LIKE', '%'.$filter.'%')
                ->orWhere('residence', 'LIKE', '%'.$filter.'%')
                ->orWhere('pickup_location', 'LIKE', '%'.$filter.'%')
                ->orWhere('notes', 'LIKE', '%'.$filter.'%')
        );
    }

    public function store(StoreCommunityVolunteer $request): JsonResponse
    {
        $this->authorize('create', CommunityVolunteer::class);

        $cmtyvol = new CommunityVolunteer();
        $cmtyvol->fill($request->all());
        $cmtyvol->languages = ($request->languages != null ? array_unique(array_map('trim', preg_split('/(\s*[,;\/|]\s*)|(\s+and\s+)/', $request->languages))) : null);

        $cmtyvol->save();

        $this->updateResponsibilities($cmtyvol, $request->input('responsibilities'));

        return response()
            ->json([
                'message' => __('Community volunteer added'),
                'id' => $cmtyvol->id,
            ]);
    }

    public function show(CommunityVolunteer $cmtyvol): JsonResource
    {
        $this->authorize('view', $cmtyvol);

        return new CommunityVolunteerResource($cmtyvol);
    }

    public function update(StoreCommunityVolunteer $request, CommunityVolunteer $cmtyvol): JsonResponse
    {
        $this->authorize('update', $cmtyvol);

        $cmtyvol->fill($request->all());
        $cmtyvol->languages = ($request->languages != null ? array_unique(array_map('trim', preg_split('/(\s*[,;\/|]\s*)|(\s+and\s+)/', $request->languages))) : null);
        $cmtyvol->save();

        $this->updateResponsibilities($cmtyvol, $request->input('responsibilities'));

        return response()
            ->json([
                'message' => __('Community volunteer updated'),
            ]);
    }

    public function destroy(CommunityVolunteer $cmtyvol): JsonResponse
    {
        $this->authorize('delete', $cmtyvol);

        $cmtyvol->delete();

        return response()
            ->json([
                'message' => __('Community volunteer deleted'),
            ]);
    }

    private function updateResponsibilities(CommunityVolunteer $cmtyvol, $value)
    {
        DB::transaction(function () use ($cmtyvol, $value) {
            $cmtyvol->responsibilities()->detach();
            if ($value != null) {
                if (! is_array($value)) {
                    $values = [];
                    foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                        $values[] = $v;
                    }
                    $value = array_map('trim', $values);
                }
                collect($value)->map(function ($entry) use ($cmtyvol) {
                    if (! is_array($entry)) {
                        $entry = ['id' => $entry];
                    }
                    $responsibility = Responsibility::where('id', $entry['id'])->first();
                    $cmtyvol->responsibilities()->attach($responsibility, [
                        'start_date' => isset($entry['start_date']) ? $entry['start_date'] : null,
                        'end_date' => isset($entry['end_date']) ? $entry['end_date'] : null,
                    ]);
                });
            }
        });
    }

    public function languages(Request $request): array
    {
        return CommunityVolunteer::query()
            ->when($request->has('activeOnly'), fn ($qry) => $qry->workStatus('active'))
            ->select('languages')
            ->distinct()
            ->whereNotNull('languages')
            ->orderBy('languages')
            ->get()
            ->pluck('languages')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    public function pickupLocations(Request $request): array
    {
        return CommunityVolunteer::query()
            ->when($request->has('activeOnly'), fn ($qry) => $qry->workStatus('active'))
            ->select('pickup_location')
            ->distinct()
            ->orderBy('pickup_location')
            ->whereNotNull('pickup_location')
            ->get()
            ->pluck('pickup_location')
            ->toArray();
    }
}
