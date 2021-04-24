<?php

namespace Database\Factories\Fundraising;

use App\Models\Fundraising\Donation;
use MrCage\EzvExchangeRates\EzvExchangeRates;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $baseCurrency = config('fundraising.base_currency');
        $currencyValidator = function ($cc) use ($baseCurrency) {
            return in_array($cc, array_merge(config('fundraising.currencies'), [$baseCurrency]));
        };
        $date = $this->faker->dateTimeBetween('-5 years', 'now');
        $amount = $this->faker->numberBetween(1, 10000);
        $currency = $this->faker->valid($currencyValidator)->currencyCode;
        $exchAmount = $currency != $baseCurrency ? $this->getExchangeRate($currency) * $amount : $amount;
        return [
            'date' => $date,
            'amount' => $amount,
            'currency' => $currency,
            'exchange_amount' => $exchAmount,
            'channel' => $this->faker->randomElement(['Cash', 'Bank Transfer', 'PayPal', 'Stripe', 'RaiseNow']),
            'purpose' => $this->faker->optional(0.2)->sentence,
            'reference' => $this->faker->optional(0.2)->bothify('********************'),
            'in_name_of' => $this->faker->optional(0.1)->name,
            'thanked' => $this->faker->optional(0.1)->dateTimeBetween($date, 'now'),
        ];
    }

    private function getExchangeRate($currency)
    {
        try {
            return EzvExchangeRates::getExchangeRate($currency);
        } catch (\Exception $ex) {
            return 1;
        }
    }
}
