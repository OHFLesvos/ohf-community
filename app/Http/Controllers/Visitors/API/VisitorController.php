<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Visitors\StoreVisitor;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Setting;

class VisitorController extends Controller
{
    public function index(Request $request)
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
            ->forFilter($filter)
            ->where('anonymized', false)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize));
    }

    public function store(StoreVisitor $request)
    {
        $this->authorize('create', Visitor::class);

        $visitor = new Visitor();
        $visitor->fill($request->all());
        $visitor->save();

        return (new VisitorResource($visitor))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Visitor $visitor)
    {
        $this->authorize('view', $visitor);

        return new VisitorResource($visitor);
    }

    public function update(Visitor $visitor, StoreVisitor $request)
    {
        $this->authorize('update', $visitor);

        $visitor->fill($request->all());
        $visitor->save();

        return new VisitorResource($visitor);
    }

    public function destroy(Visitor $visitor)
    {
        $this->authorize('delete', $visitor);

        $visitor->delete();

        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }

    public function checkin(Visitor $visitor, Request $request)
    {
        $this->authorize('update', $visitor);

        $request->validate([
            'purpose_of_visit' => [
                'nullable',
                Rule::in(Setting::get('visitors.purposes_of_visit', [])),
            ]
        ]);

        $checkin = new VisitorCheckin();
        $checkin->fill($request->all());
        $visitor->checkins()->save($checkin);

        return response()
            ->json($this->getCheckedInData());
    }

    public function export()
    {
        $this->authorize('export-visitors');

        $file_name = __('Visitors') . ' as of ' . now()->toDateString();
        $extension = 'xlsx';
        return (new VisitorsExport())->download($file_name . '.' . $extension);
    }

    public function checkins()
    {
        return response()
            ->json($this->getCheckedInData());
    }

    private function getCheckedInData(): array
    {
        return [
            'checked_in_today' => VisitorCheckin::whereDate('created_at', today()->toDateString())->count(),
        ];
    }

    public function dailyVisitors(Request $request)
    {
        $this->authorize('viewAny', Visitor::class);

        $request->validate([
            'days' => [
                'nullable',
                'int',
                'min:1',
            ]
        ]);
        $maxNumberOfActiveDays = $request->input('days', 10);

        return VisitorCheckin::query()
            ->selectRaw('DATE(created_at) as day')
            ->addSelect(
                collect(Setting::get('visitors.purposes_of_visit', []))
                    ->mapWithKeys(fn ($t, $k) => [
                        $t => VisitorCheckin::selectRaw('COUNT(*)')
                            ->whereRaw('DATE(created_at) = day')
                            ->where('purpose_of_visit', $t)
                    ])
                    ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day', 'desc')
            ->limit($maxNumberOfActiveDays)
            ->get();
    }

    public function monthlyVisitors()
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
                            ->where('purpose_of_visit', $t)
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
