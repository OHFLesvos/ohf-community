<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Exports\Fundraising\DonorsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Fundraising\StoreDonor;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Fundraising\Donor as DonorResource;
use App\Http\Resources\Fundraising\DonorCollection;
use App\Models\Accounting\Budget;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use JeroenDesloovere\VCard\VCard;

class DonorController extends Controller
{
    use ValidatesResourceIndex;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Donor::class);

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
                    'company',
                    'city',
                    'country',
                    'language',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
            'tags' => [
                'nullable',
                'array',
            ],
            'tags.*' => [
                'alpha_dash',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'first_name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 10);
        $filter = trim($request->input('filter', ''));
        $tags = $request->input('tags', []);

        if ($sortBy == 'country') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->get()
                ->$sortMethod('country_name')
                ->paginate($pageSize);
        } elseif ($sortBy == 'language') {
            $sortMethod = $sortDirection == 'desc' ? 'sortByDesc' : 'sortBy';
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->get()
                ->$sortMethod('language')
                ->paginate($pageSize);
        } else {
            $donors = Donor::query()
                ->withAllTags($tags)
                ->forFilter($filter)
                ->orderBy($sortBy, $sortDirection)
                ->paginate($pageSize);
        }

        return new DonorCollection($donors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonor $request)
    {
        $this->authorize('create', Donor::class);

        $donor = new Donor();
        $donor->fill($request->all());

        $donor->save();

        return response()->json([
            'message' => __('Donor added'),
            'id' => $donor->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function show(Donor $donor, Request $request)
    {
        $this->authorize('view', $donor);

        return new DonorResource($donor, $request->has('extended'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonor $request, Donor $donor)
    {
        $this->authorize('update', $donor);

        $donor->fill($request->all());
        $donor->save();

        return response()->json([
            'message' => __('Donor updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor)
    {
        $this->authorize('delete', $donor);

        $donor->delete();

        return response()->json([
            'message' => __('Donor deleted'),
        ]);
    }

    /**
     * Gets all salutations assigned to donors
     *
     * @return \Illuminate\Http\Response
     */
    public function salutations()
    {
        return response()->json([
            'data' => Donor::salutations(),
        ]);
    }

    /**
     * Download vcard
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function vcard(Donor $donor)
    {
        $this->authorize('view', $donor);

        // define vcard
        $vcard = new VCard();
        if ($donor->company != null) {
            $vcard->addCompany($donor->company);
        }
        if ($donor->last_name != null || $donor->first_name != null) {
            $vcard->addName($donor->last_name, $donor->first_name, '', '', '');
        }
        if ($donor->email != null) {
            $vcard->addEmail($donor->email);
        }
        if ($donor->phone != null) {
            $vcard->addPhoneNumber($donor->phone, $donor->company != null ? 'WORK' : 'HOME');
        }
        $vcard->addAddress(null, null, $donor->street, $donor->city, null, $donor->zip, $donor->country_name, ($donor->company != null ? 'WORK' : 'HOME').';POSTAL');

        // return vcard as a download
        return $vcard->download();
    }

    /**
     * Exports a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('viewAny', Donor::class);

        $extension = 'xlsx';

        $file_name = sprintf(
            '%s - %s (%s).%s',
            config('app.name'),
            __('Donors'),
            Carbon::now()->toDateString(),
            $extension
        );

        return (new DonorsExport())->download($file_name);
    }

    public function budgets(Donor $donor)
    {
        $this->authorize('view', $donor);
        $this->authorize('viewAny', Budget::class);

        $this->validatePagination();

        $data = $donor->budgets()->paginate($this->getPageSize(10));

        return BudgetResource::collection($data);
    }

    public function names()
    {
        $this->authorize('viewAny', Donor::class);

        return Donor::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company')
            ->get()
            ->map(fn ($donor) => [
                'id' => $donor->id,
                'name' => $donor->fullName,
            ]);
    }
}
