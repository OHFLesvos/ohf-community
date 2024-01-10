<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Exports\Fundraising\DonorsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\Fundraising\StoreDonor;
use App\Http\Resources\Accounting\Budget as BudgetResource;
use App\Http\Resources\Fundraising\Donor as DonorResource;
use App\Models\Accounting\Budget;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use JeroenDesloovere\VCard\VCard;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DonorController extends Controller
{
    use ValidatesResourceIndex;

    public function index(Request $request): JsonResource
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
                    'country_name',
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

        if ($sortBy == 'country_name') {
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

        return DonorResource::collection($donors);
    }

    public function store(StoreDonor $request): JsonResponse
    {
        $this->authorize('create', Donor::class);

        $donor = new Donor();
        $donor->fill($request->all());

        $donor->save();

        return response()
            ->json([
                'message' => __('Donor added'),
                'id' => $donor->id,
            ]);
    }

    public function show(Donor $donor): JsonResource
    {
        $this->authorize('view', $donor);

        return new DonorResource($donor->loadCount('donations')->loadCount('budgets'));
    }

    public function update(StoreDonor $request, Donor $donor): JsonResponse
    {
        $this->authorize('update', $donor);

        $donor->fill($request->all());
        $donor->save();

        return response()
            ->json([
                'message' => __('Donor updated'),
            ]);
    }

    public function destroy(Donor $donor): JsonResponse
    {
        $this->authorize('delete', $donor);

        $donor->delete();

        return response()
            ->json([
                'message' => __('Donor deleted'),
            ]);
    }

    public function salutations(): JsonResponse
    {
        return response()
            ->json([
                'data' => Donor::salutations(),
            ]);
    }

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
        $vcard->addAddress(
            street: $donor->street,
            city: $donor->city,
            zip: $donor->zip,
            country: $donor->country_name,
            type: ($donor->company != null ? 'WORK' : 'HOME').';POSTAL',
        );

        // return vcard as a download
        return $vcard->download();
    }

    public function export(Request $request): BinaryFileResponse
    {
        $this->authorize('viewAny', Donor::class);

        $request->validate([
            'format' => Rule::in('xlsx'),
            'includeChannels' => 'boolean',
            'showAllDonors' => 'boolean',
            'year' => ['integer', Rule::in(Donation::years())],
        ]);

        $extension = $request->input('format', 'xlsx');

        $year = $request->input('year', null);
        $includeChannels = $request->boolean('includeChannels');
        $showAllDonors = $request->boolean('showAllDonors');

        $file_name = sprintf(
            '%s - %s (%s).%s',
            config('app.name'),
            __('Donors').($year !== null ? (' '.intval($year)) : ''),
            __('as of :date', ['date' => Carbon::now()->isoFormat('LL')]),
            $extension
        );

        return (new DonorsExport(year: $year, includeChannels: $includeChannels, showAllDonors: $showAllDonors))->download($file_name);
    }

    public function budgets(Donor $donor): JsonResource
    {
        $this->authorize('view', $donor);
        $this->authorize('viewAny', Budget::class);

        $this->validatePagination();

        $data = $donor->budgets()->paginate($this->getPageSize(10));

        return BudgetResource::collection($data);
    }

    public function names(): Collection
    {
        $this->authorize('viewAny', Donor::class);

        return Donor::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company')
            ->get()
            ->map(fn (Donor $donor) => [
                'id' => $donor->id,
                'name' => $donor->full_name,
            ]);
    }
}
