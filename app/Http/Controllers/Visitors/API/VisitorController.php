<?php

namespace App\Http\Controllers\Visitors\API;

use App\Exports\Visitors\VisitorExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use App\Models\Visitors\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class VisitorController extends Controller
{
    const TYPES = [
        'visitors' => 'visitor',
        'participants' => 'participant',
        'staff' => 'staff',
        'external' => 'external',
    ];

    public function listCurrent(Request $request)
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
                    'first_name',
                    'last_name',
                    'id_number',
                    'place_of_residence',
                    'activity',
                    'organization',
                    'entered_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'last_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 100);
        $filter = trim($request->input('filter', ''));

        return VisitorResource::collection(Visitor::query()
            ->whereNull('left_at')
            ->whereDate('entered_at', today())
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->orderBy('first_name')
            ->paginate($pageSize))
            ->additional(['meta' => [
                'currently_visiting' => collect(self::TYPES)
                    ->mapWithKeys(fn ($t, $k) => [
                        $k => Visitor::query()
                                ->whereNull('left_at')
                                ->whereDate('entered_at', today())
                                ->where('type', $t)
                                ->count()
                    ])
                    ->toArray(),
            ]]);
    }

    public function checkin(Request $request)
    {
        $this->authorize('create', Visitor::class);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => [
                'required',
                Rule::in(['visitor', 'participant', 'staff', 'external']),
            ],
        ]);

        $visitor = new Visitor();
        $visitor->first_name = $request->first_name;
        $visitor->last_name = $request->last_name;
        $visitor->type = $request->type;
        $visitor->id_number = $request->id_number;
        $visitor->place_of_residence = $request->place_of_residence;
        $visitor->activity = $request->activity;
        $visitor->organization = $request->organization;
        $visitor->entered_at = now();
        $visitor->save();

        return response()
            ->json([], Response::HTTP_CREATED);
    }

    public function checkout(Visitor $visitor)
    {
        $this->authorize('update', $visitor);

        if ($visitor->left_at !== null) {
            abort(Response::HTTP_CONFLICT);
        }

        $visitor->left_at = now();
        $visitor->save();

        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }

    public function checkoutAll()
    {
        $this->authorize('updateAny', Visitor::class);

        Visitor::query()
            ->whereNull('left_at')
            ->update([
                'left_at' => now(),
            ]);

        return response()
            ->json([], Response::HTTP_NO_CONTENT);
    }

    public function export(Request $request)
    {
        $this->authorize('export-visitors');

        $file_name = __('app.visitors') . ' as of ' . now()->toDateString();
        $extension = 'xlsx';
        return (new VisitorExport())->download($file_name . '.' . $extension);
    }

    public function dailyVisitors()
    {
        $this->authorize('viewAny', Visitor::class);

        $maxNumberOfActiveDays = 30;

        return Visitor::query()
            ->selectRaw('DATE(entered_at) as day')
            ->addSelect(
                collect(self::TYPES)
                ->mapWithKeys(fn ($t, $k) => [
                    $k => Visitor::selectRaw('COUNT(*)')
                        ->whereRaw('DATE(entered_at) = day')
                        ->where('type', $t)
                ])
                ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(entered_at)')
            ->orderBy('day', 'desc')
            ->limit($maxNumberOfActiveDays)
            ->get();
    }

    public function monthlyVisitors()
    {
        $this->authorize('viewAny', Visitor::class);

        return Visitor::query()
            ->selectRaw('MONTH(entered_at) as month')
            ->selectRaw('YEAR(entered_at) as year')
            ->addSelect(
                collect(self::TYPES)
                ->mapWithKeys(fn ($t, $k) => [
                    $k => Visitor::selectRaw('COUNT(*)')
                        ->whereRaw('MONTH(entered_at) = month')
                        ->whereRaw('YEAR(entered_at) = year')
                        ->where('type', $t)
                ])
                ->toArray()
            )
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('MONTH(entered_at)')
            ->groupByRaw('YEAR(entered_at)')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }
}
