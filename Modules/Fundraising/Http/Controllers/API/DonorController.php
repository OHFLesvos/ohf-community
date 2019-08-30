<?php

namespace Modules\Fundraising\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Transformers\DonorCollection;

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
                'filled',
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc'
            ],
        ]);

        // Sorting and pagination
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);

        if ($sortBy == 'country') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = Donor::query()
                ->get()
                ->$sortMethod('country_name')
                ->paginate($pageSize);
        } else {
            $donors = Donor::query()
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);
        }
        return new DonorCollection($donors);            
    }
}
