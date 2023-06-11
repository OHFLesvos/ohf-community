<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Visitors\StoreVisitor;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use App\Models\Visitors\ParentChild;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Setting;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VisitorController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $this->authorize('viewAny', Visitor::class);

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
                    'id_number',
                    'date_of_birth',
                    'gender',
                    'nationality',
                    'living_situation',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 100);
        $filter = trim($request->input('filter', ''));

        return VisitorResource::collection(Visitor::query()
            ->with(['checkins', 'parents', 'children'])
            ->when(! empty($filter), fn (Builder $query) => $this->filterQuery($query, $filter))
            ->where('anonymized', false)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize));
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where(fn (Builder $wq) => $wq
            ->where('name', 'LIKE', '%'.$filter.'%')
            ->orWhere('id_number', 'LIKE', '%'.$filter.'%')
            ->orWhereDate('date_of_birth', $filter));
    }

    public function store(StoreVisitor $request): JsonResponse
    {
        $this->authorize('create', Visitor::class);

        $visitor = new Visitor();
        $visitor->fill($request->all());
        $visitor->save();

        return (new VisitorResource($visitor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Visitor $visitor): JsonResource
    {
        $this->authorize('view', $visitor);

        $visitor->load('parents', 'children');

        return new VisitorResource($visitor);
    }

    public function createParentChild($parentId, $childId)
    {
        $parent = Visitor::findOrFail($parentId);
        $child = Visitor::findOrFail($childId);

        $parentChild = new ParentChild();
        $parentChild->parent_id = $parent->id;
        $parentChild->child_id = $child->id;

        $parentChild->save();
        return;
    }

    public function deleteParentChild($parentId, $childId) 
    {   
        $parentChild = ParentChild::where('parent_id', $parentId)
            ->where('child_id', $childId)
            ->firstOrFail();

        $parentChild->delete();
        return;
    }

    public function updateChildren(Visitor $visitor, StoreVisitor $request)
    {
        $this->authorize('update', $visitor);

        $childrenIds = collect($request->input('children', []))
            ->map(fn ($child) => $child['id']);
        $existingChildrenIds = $visitor->children()->get()
            ->map(fn ($child) => $child->id);

        $parentId = $visitor->id;
        $createChildrenIds = $childrenIds->diff($existingChildrenIds);
        $deleteChildrenIds = $existingChildrenIds->diff($childrenIds);

        foreach ($createChildrenIds as $childId) {
            $this->createParentChild($parentId, $childId);
        }

        foreach ($deleteChildrenIds as $childId) {
            $this->deleteParentChild($parentId, $childId);
        }
        return;
    }

    public function updateParents(Visitor $visitor, StoreVisitor $request)
    {
        $this->authorize('update', $visitor);

        $parentIds = collect($request->input('parents', []))
            ->map(fn ($parent) => $parent['id']);
        $existingParentIds = $visitor->parents()->get()
            ->map(fn ($parent) => $parent->id);

        $childId = $visitor->id;
        $createParentIds = $parentIds->diff($existingParentIds);
        logger()->info('Update', [
            'Request parent ids' => $parentIds,
            'Existing parent ids' => $existingParentIds,
        ]);
        $deleteParentIds = $existingParentIds->diff($parentIds); //Broken line???
        // Error message: Call to a member function getKey() on int

        foreach ($createParentIds as $parentId) {
            $this->createParentChild($parentId, $childId);
        }

        foreach ($deleteParentIds as $parentId) {
            $this->deleteParentChild($parentId, $childId);
        }
        return;
    }

    public function update(Visitor $visitor, StoreVisitor $request): JsonResource
    {
        $this->authorize('update', $visitor);

        $this->updateChildren($visitor, $request);
        $this->updateParents($visitor, $request);

        $visitor->fill($request->all());
            $visitor->save();

        return new VisitorResource($visitor);
    }

    public function destroy(Visitor $visitor): JsonResponse
    {
        $this->authorize('delete', $visitor);

        $visitor->delete();

        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }

    public function checkin(Visitor $visitor, Request $request): JsonResponse
    {
        $this->authorize('update', $visitor);

        $request->validate([
            'purpose_of_visit' => [
                'nullable',
                Rule::in(Setting::get('visitors.purposes_of_visit', [])),
            ],
        ]);

        $checkin = new VisitorCheckin();
        $checkin->fill($request->all());
        $visitor->checkins()->save($checkin);

        return response()
            ->json($this->getCheckedInData());
    }

    public function signLiabilityForm(Visitor $visitor): JsonResource
    {
        $this->authorize('update', $visitor);

        $visitor->liability_form_signed = today();
        $visitor->save();

        return new VisitorResource($visitor);
    }

    public function giveParentalConsent(Visitor $visitor): JsonResource
    {
        $this->authorize('update', $visitor);

        $visitor->parental_consent_given = true;
        $visitor->save();

        return new VisitorResource($visitor);
    }

    public function export(): BinaryFileResponse
    {
        $this->authorize('export-visitors');

        $file_name = __('Visitors').' as of '.now()->toDateString();
        $extension = 'xlsx';

        return (new VisitorsExport())->download($file_name.'.'.$extension);
    }

    public function checkins(): JsonResponse
    {
        return response()
            ->json($this->getCheckedInData());
    }

    private function getCheckedInData(): array
    {
        return [
            'checked_in_today' => VisitorCheckin::query()
                ->whereDate('created_at', today()->toDateString())
                ->count(),
        ];
    }

    public function dailyVisitors(Request $request): Collection
    {
        $this->authorize('viewAny', Visitor::class);

        $request->validate([
            'days' => [
                'nullable',
                'int',
                'min:1',
            ],
        ]);
        $maxNumberOfActiveDays = $request->input('days', 10);

        return VisitorCheckin::query()
            ->selectRaw('DATE(created_at) as day')
            ->addSelect(
                collect(Setting::get('visitors.purposes_of_visit', []))
                    ->mapWithKeys(fn ($t, $k) => [
                        $t => VisitorCheckin::selectRaw('COUNT(*)')
                            ->whereRaw('DATE(created_at) = day')
                            ->where('purpose_of_visit', $t),
                    ])
                    ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day', 'desc')
            ->limit($maxNumberOfActiveDays)
            ->get();
    }

    public function monthlyVisitors(): Collection
    {
        $this->authorize('viewAny', Visitor::class);

        return VisitorCheckin::query()
            ->selectRaw('MONTH(created_at) as month')
            ->selectRaw('YEAR(created_at) as year')
            ->addSelect(
                collect(Setting::get('visitors.purposes_of_visit', []))
                    ->mapWithKeys(fn ($t, $k) => [
                        $t => VisitorCheckin::selectRaw('COUNT(*)')
                            ->whereRaw('MONTH(created_at) = month')
                            ->whereRaw('YEAR(created_at) = year')
                            ->where('purpose_of_visit', $t),
                    ])
                    ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->groupByRaw('YEAR(created_at)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }
}
