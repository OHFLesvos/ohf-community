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

    public int $importedDonors = 0;

    public int $importedDonations = 0;

    public function collection(Collection $rows): void
    {
        $this->importedDonors = 0;
        $this->importedDonations = 0;

        foreach ($rows as $row) {
            Validator::make($rows->toArray(), [
                '*.status' => 'required',
            ])->validate();

            if ($row['status'] == 'Paid') {
                $donor = Donor::where('email', $row['customer_email'])
                    ->first();
                if ($donor == null) {
                    $donor = new Donor();
                    $nameArr = explode(' ', $row['customer_name_metadata']);
                    if (count($nameArr) > 1) {
                        $donor->last_name = array_pop($nameArr);
                        $donor->first_name = implode(' ', $nameArr);
                    } else {
                        $donor->first_name = $row['customer_name_metadata'];
                    }
                    $donor->email = $row['customer_email'];
                    $donor->street = $row['shipping_address_line1'].($row['shipping_address_line2'] !== null ? ', '.$row['shipping_address_line2'] : '');
                    $donor->city = $row['shipping_address_city'];
                    $donor->zip = $row['shipping_address_postal_code'];
                    $donor->country_code = $row['shipping_address_country'];
                    $donor->phone = $row['customer_phone'];
                    $donor->save();
                    $this->importedDonors++;
                }

                $date = new Carbon($row['created_date_utc'] ?? $row['created_utc']);
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

                    $donor->addDonation($donation);
                    $this->importedDonations++;
                }
            }
        }
    }
}
