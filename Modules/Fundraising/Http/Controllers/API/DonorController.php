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
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc'
            ],
        ]);

        // Sorting
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        if ($sortBy == 'country') {
            // TODO: Only country-code sorting possible, tricky to resolve localized names
            $sortBy = 'country_code';
        }

        $donors = Donor::query()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($request->input('pageSize', 10));
        return new DonorCollection($donors);            
    }
}
