<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Exports\Fundraising\DonationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonation;
use App\Http\Resources\Fundraising\Donation as DonationResource;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Imports\Fundraising\DonationsImport;
use App\Models\Fundraising\Donation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use MrCage\EzvExchangeRates\EzvExchangeRates;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DonationController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $this->authorize('viewAny', Donation::class);

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
                    'date',
                    'exchange_amount',
                    'in_name_of',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'created_at');
        $sortDirection = $request->input('sortDirection', 'desc');
        $pageSize = $request->input('pageSize', 100);
        $filter = trim($request->input('filter', ''));

        $donations = Donation::query()
            ->with(['donor', 'budget'])
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize);

        return new DonationCollection($donations);
    }

    public function show(Donation $donation): JsonResource
    {
        $this->authorize('view', $donation);

        return new DonationResource($donation->load('donor'));
    }

    public function update(StoreDonation $request, Donation $donation): JsonResponse
    {
        $this->authorize('update', $donation);

        $date = new Carbon($request->date);
        if ($date->isFuture()) {
            $date = Carbon::today();
        }

        if ($request->currency == config('fundraising.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            if (! empty($request->exchange_rate)) {
                $exchange_rate = $request->exchange_rate;
            } else {
                try {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($request->currency, $date);
                } catch (Exception $e) {
                    Log::error($e);

                    return response()
                        ->json([
                            'message' => __('An error happened').': '.$e->getMessage(),
                        ], Response::HTTP_SERVICE_UNAVAILABLE);
                }
            }
            $exchange_amount = $request->amount * $exchange_rate;
        }

        $donation->date = $date;
        $donation->amount = $request->amount;
        $donation->currency = $request->currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->channel = $request->channel;
        $donation->purpose = $request->purpose;
        $donation->reference = $request->reference;
        $donation->in_name_of = $request->in_name_of;
        $donation->thanked = ! empty($request->thanked) ? ($donation->thanked !== null ? $donation->thanked : Carbon::now()) : null;

        if ($request->filled('budget_id')) {
            $donation->budget()->associate($request->budget_id);
        } else {
            $donation->budget()->dissociate();
        }

        $donation->save();

        return response()
            ->json([
                'message' => __('Donation has been updated'),
            ]);
    }

    public function destroy(Donation $donation): JsonResponse
    {
        $this->authorize('delete', $donation);

        $donation->delete();

        return response()
            ->json([
                'message' => __('Donation has been deleted'),
            ]);
    }

    public function channels(): JsonResponse
    {
        $this->authorize('viewAny', Donation::class);

        return response()
            ->json([
                'data' => Donation::channels(),
            ]);
    }

    public function currencies(): JsonResponse
    {
        $this->authorize('viewAny', Donation::class);

        return response()->json([
            'data' => config('fundraising.currencies'),
            'meta' => [
                'base_currency' => config('fundraising.base_currency'),
            ],
        ]);
    }

    public function years(): JsonResponse
    {
        $this->authorize('viewAny', Donation::class);

        return response()
            ->json([
                'data' => Donation::years(),
            ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $this->authorize('viewAny', Donation::class);

        $request->validate([
            'format' => Rule::in('xlsx'),
            'includeAddress' => 'boolean',
            'year' => ['integer', Rule::in(Donation::years())],
        ]);

        $extension = $request->input('format', 'xlsx');

        $includeAddress = $request->boolean('includeAddress');
        $year = $request->input('year', null);

        $file_name = sprintf(
            '%s - %s (%s).%s',
            config('app.name'),
            __('Donations').($year !== null ? (' '.intval($year)) : ''),
            __('as of :date', ['date' => Carbon::now()->isoFormat('LL')]),
            $extension
        );

        return (new DonationsExport(includeAddress: $includeAddress, year: $year))->download($file_name);
    }

    public function import(Request $request): JsonResponse
    {
        $this->authorize('create', Donation::class);

        $request->validate([
            'type' => [
                'required',
                Rule::in(['stripe']),
            ],
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
        ]);

        (new DonationsImport())->import($request->file('file'));

        return response()
            ->json([
                'message' => __('Import successful.'),
            ]);
    }
}
