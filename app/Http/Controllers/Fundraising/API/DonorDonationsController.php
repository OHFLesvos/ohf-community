<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Exports\Fundraising\DonationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonation;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use MrCage\EzvExchangeRates\EzvExchangeRates;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DonorDonationsController extends Controller
{
    public function index(Donor $donor, Request $request): JsonResource
    {
        $this->authorize('viewAny', Donation::class);
        $this->authorize('view', $donor);

        $request->validate([
            'year' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);
        $year = $request->input('year');

        $donations = $donor->donations()
            ->with(['budget'])
            ->when($year, fn ($qry) => $qry->forYear($year))
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return (new DonationCollection($donations))
            ->additional([
                'meta' => [
                    'can_create' => $request->user()->can('create', Donation::class),
                ],
            ]);
    }

    public function store(StoreDonation $request, Donor $donor): JsonResponse
    {
        $this->authorize('create', Donation::class);

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

        $donation = new Donation();
        $donation->date = $date;
        $donation->amount = $request->amount;
        $donation->currency = $request->currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->channel = $request->channel;
        $donation->purpose = $request->purpose;
        $donation->reference = $request->reference;
        $donation->in_name_of = $request->in_name_of;
        $donation->thanked = ! empty($request->thanked) ? Carbon::now() : null;

        if ($request->filled('budget_id')) {
            $donation->budget()->associate($request->budget_id);
        } else {
            $donation->budget()->dissociate();
        }

        $donor->addDonation($donation);

        return response()
            ->json([
                'message' => __('Donation of :currency :amount has been registered', ['amount' => $request->amount, 'currency' => $request->currency]),
            ]);
    }

    public function export(Donor $donor): BinaryFileResponse
    {
        $this->authorize('viewAny', Donation::class);

        $extension = 'xlsx';

        $file_name = sprintf(
            '%s - %s - %s (%s).%s',
            config('app.name'),
            __('Donations'),
            $donor->full_name,
            Carbon::now()->toDateString(),
            $extension
        );

        return (new DonationsExport($donor))->download($file_name);
    }
}
