<?php

namespace App\Http\Controllers\Visitors\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Requests\Visitors\StoreVisitor;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use App\Models\Visitors\VisitorParentChild;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Setting;

class VisitorController extends Controller
{
    use ValidatesDateRanges;

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
                    'membership_number',
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
        $words = array_filter(preg_split('/\s+/', $filter), fn ($w) => preg_match('/\w/', $w));

        return $query->where(fn (Builder $wq) => $wq
            ->where('name', 'LIKE', '%'.$filter.'%')
            ->orWhereRaw('MATCH(name) against (? IN BOOLEAN MODE)', implode(' ', array_map(fn ($w) => '+'.$w, $words)))
            ->orWhere('id_number', 'LIKE', $filter.'%')
            ->orWhere('membership_number', 'LIKE', $filter.'%')
            ->orWhereDate('date_of_birth', $filter)
        );
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

        $parentChild = new VisitorParentChild();
        $parentChild->parent_id = $parent->id;
        $parentChild->child_id = $child->id;

        $parentChild->save();
    }

    public function deleteParentChild($parentId, $childId)
    {
        $parentChild = VisitorParentChild::where('parent_id', $parentId)
            ->where('child_id', $childId)
            ->firstOrFail();

        $parentChild->delete();
    }

    public function updateChildren(Visitor $visitor, StoreVisitor $request)
    {
        $this->authorize('update', $visitor);

        $parentId = $visitor->id;

        $childrenIds = array_column($request->input('children', []), 'id');
        $existingChildrenIds = $visitor->children()
            ->pluck('visitors.id')
            ->toArray();

        $deleteChildrenIds = array_diff($existingChildrenIds, $childrenIds);
        $createChildrenIds = array_diff($childrenIds, $existingChildrenIds);

        foreach ($createChildrenIds as $childId) {
            $this->createParentChild($parentId, $childId);
        }

        foreach ($deleteChildrenIds as $childId) {
            $this->deleteParentChild($parentId, $childId);
        }
    }

    public function updateParents(Visitor $visitor, StoreVisitor $request)
    {
        $this->authorize('update', $visitor);

        $childId = $visitor->id;

        $parentIds = array_column($request->input('parents', []), 'id');
        $existingParentIds = $visitor->parents()
            ->pluck('visitors.id')
            ->toArray();

        $deleteParentIds = array_diff($existingParentIds, $parentIds);
        $createParentIds = array_diff($parentIds, $existingParentIds);

        foreach ($createParentIds as $parentId) {
            $this->createParentChild($parentId, $childId);
        }

        foreach ($deleteParentIds as $parentId) {
            $this->deleteParentChild($parentId, $childId);
        }
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
}
