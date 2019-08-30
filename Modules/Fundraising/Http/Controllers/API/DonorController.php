<?php

namespace Modules\Fundraising\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Fundraising\Entities\Donor;

use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donor::class);

        $request->validate([
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
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc'
            ],
        ]);

        // Limit & offset
        $page = $request->input('page', 1);
        $size = $request->input('pageSize', 10);
        $skip = ($page - 1) * $size;
        $take = $size;

        // Sorting
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        if ($sortBy == 'country') {
            // TODO: Only country-code sorting possible, tricky to resolve localized names
            $sortBy = 'country_code';
        }

        return Donor::query()
            ->orderBy($sortBy, $sortDirection)
            ->skip($skip)
            ->take($take)
            ->get()
            ->map(function ($donor) {
                $donor['url'] = route('fundraising.donors.show', $donor);
                $donor['country'] = $donor->country_name;
                return $donor;
            });
    }
}
