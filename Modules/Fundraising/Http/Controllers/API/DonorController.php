<?php

namespace Modules\Fundraising\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Transformers\DonorCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc'
            ],
            'tags' => [
                'nullable',
                'array',
                //                 'alpha_dash', TODO
            ]
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);
        $filter = trim($request->input('filter', ''));
        $tags = $request->input('tags', []);
            
        if ($sortBy == 'country') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = self::createQuery($filter, $tags)
                ->get()
                ->$sortMethod('country_name')
                ->paginate($pageSize);
        } else {
            $donors = self::createQuery($filter, $tags)
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);
        }
        return new DonorCollection($donors);            
    }

    private static function createQuery(String $filter, Array $tags = [])
    {
        $query = Donor::query();

        // Filter by tags
        if (count($tags) > 0) {
            $query->whereHas('tags', function($query) use($tags) {
                $query->whereIn('slug', $tags);
            });
        }

        // Filter by filter string
        if (!empty($filter)) {
            self::applyFilter($query, $filter);
        }

        return $query;
    }

    private static function applyFilter(&$query, $filter)
    {
        $query->where(function($wq) use($filter) {
            $countries = \Countries::getList(\App::getLocale());
            array_walk($countries, function(&$value, $idx){
                $value = strtolower($value);
            });
            $countries = array_flip($countries);
            return $wq->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
                ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
                ->orWhere('company', 'LIKE', '%' . $filter . '%')
                ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
                ->orWhere('last_name', 'LIKE', '%' . $filter . '%')
                ->orWhere('street', 'LIKE', '%' . $filter . '%')
                ->orWhere('zip', $filter)
                ->orWhere('city', 'LIKE', '%' . $filter . '%')
                ->orWhere(DB::raw('CONCAT(street, \' \', city)'), 'LIKE', '%' . $filter . '%')
                ->orWhere(DB::raw('CONCAT(street, \' \', zip, \' \', city)'), 'LIKE', '%' . $filter . '%')
                // Note: Countries filter only works for complete country code or country name
                ->orWhere('country_code', $countries[strtolower($filter)] ?? $filter)
                ->orWhere('email', 'LIKE', '%' . $filter . '%')
                ->orWhere('phone', 'LIKE', '%' . $filter . '%');
        });
    }
}
