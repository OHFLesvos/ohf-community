<?php

namespace App\Http\Controllers\Visitors\API;

use App\Http\Controllers\Controller;
use App\Models\Visitors\Visitor;
use App\Http\Resources\Visitors\Visitor as VisitorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class VisitorController extends Controller
{
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
            ->paginate($pageSize ));
    }

    public function checkin(Request $request)
    {
        $this->authorize('create', Visitor::class);

        $visitor = new Visitor();
        $visitor->first_name = $request->first_name;
        $visitor->last_name = $request->last_name;
        $visitor->id_number = $request->id_number;
        $visitor->place_of_residence = $request->place_of_residence;
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
}
