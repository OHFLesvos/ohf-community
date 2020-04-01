<?php

namespace App\Imports\Fundraising;

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MrCage\EzvExchangeRates\EzvExchangeRates;

class DonationsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Validator::make($rows->toArray(), [
                '*.status' => 'required',
            ])->validate();

            if ($row['status'] == 'Paid') {

                $donor = Donor::where('email', $row['customer_email'])
                    ->first();
                if ($donor == null) {
                    $donor = new Donor();
                    $donor->first_name = preg_replace('/@.*$/', '', $row['customer_email']);
                    $donor->email = $row['customer_email'];
                    $donor->save();
                }

                $date = new Carbon($row['created_utc']);
                $amount = $row['amount'];
                $currency = strtoupper($row['currency']);
                if ($currency != config('fundraising.base_currency')) {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($currency, $date);
                    $exchange_amount = $amount * $exchange_rate;
                } else {
                    $exchange_amount = $amount;
                }

                $donation = Donation::where('channel', 'Stripe')
                    ->where('reference', $row['id'])
                    ->first();
                if ($donation == null) {
                    $donation = new Donation();
                    $donation->date = $date;
                    $donation->amount = $amount;
                    $donation->currency = $currency;
                    $donation->exchange_amount = $exchange_amount;
                    $donation->channel = 'Stripe';
                    $donation->reference = $row['id'];
                    $donation->purpose = $row['description'];
                    $donor->donations()->save($donation);
                }
            }
        }
    }
}
