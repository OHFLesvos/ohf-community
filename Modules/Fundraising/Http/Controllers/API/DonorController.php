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
            'size' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $offset = ($page - 1) * $size;
        $limit = $size;

        return Donor::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($donor) {
                $donor['url'] = route('fundraising.donors.show', $donor);
                $donor['country'] = $donor->country_name;
                return $donor;
            });
    }
}
