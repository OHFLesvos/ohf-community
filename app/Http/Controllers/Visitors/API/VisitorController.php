<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Requests\Visitors\StoreVisitor;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
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
            ->with('checkins')
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

        return new VisitorResource($visitor);
    }

    public function update(Visitor $visitor, StoreVisitor $request): JsonResource
    {
        $this->authorize('update', $visitor);

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
}
