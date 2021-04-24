<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Exports\Fundraising\DonationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonation;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Http\Resources\Fundraising\Donation as DonationResource;
use App\Imports\Fundraising\DonationsImport;
use App\Models\Fundraising\Donation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use MrCage\EzvExchangeRates\EzvExchangeRates;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
            ->with('donor')
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize);

        return new DonationCollection($donations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show(Donation $donation)
    {
        $this->authorize('view', $donation);

        return new DonationResource($donation->load('donor'));
    }

    /**
     * Updates a donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonation $request, Donation $donation)
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
                    return response()->json([
                        'message' =>  __('app.an_error_happened'). ': ' . $e->getMessage(),
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
        $donation->save();

        return response()->json([
            'message' => __('fundraising.donation_updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);

        $donation->delete();

        return response()->json([
            'message' => __('fundraising.donation_deleted'),
        ]);
    }

    /**
     * Gets all channels assigned to donations
     *
     * @return \Illuminate\Http\Response
     */
    public function channels()
    {
        $this->authorize('viewAny', Donation::class);

        return response()->json([
            'data' => Donation::channels(),
        ]);
    }

    /**
     * Gets all currencies
     *
     * @return \Illuminate\Http\Response
     */
    public function currencies()
    {
        $this->authorize('viewAny', Donation::class);

        return response()->json([
            'data' => config('fundraising.currencies'),
            'meta' => [
                'base_currency' => config('fundraising.base_currency'),
            ],
        ]);
    }

    /**
     * Exports all donations
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('viewAny', Donation::class);

        $extension = 'xlsx';

        $file_name = sprintf(
            '%s - %s (%s).%s',
            config('app.name'),
            __('fundraising.donations'),
            Carbon::now()->toDateString(),
            $extension
        );

        return (new DonationsExport())->download($file_name);
    }

    /**
     * Imports donations from uploaded file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $this->authorize('create', Donation::class);

        $request->validate([
            'type' => [
                'required',
                Rule::in([ 'stripe' ]),
            ],
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv'
            ],
        ]);

        (new DonationsImport())->import($request->file('file'));

        return response()->json([
            'message' => __('app.import_successful'),
        ]);
    }
}
