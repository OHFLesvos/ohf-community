<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Faker\Generator as Faker;
use MrCage\EzvExchangeRates\EzvExchangeRates;

$factory->define(Donor::class, function (Faker $faker) {
    $countryCodeValidator = function ($cc) {
        return ! in_array($cc, ['HM', 'BV']); // not supported by country code library
    };
    $gender = $faker->randomElement(['male', 'female']);
    return [
        'salutation' => $faker->title($gender),
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'company' => $faker->optional(0.2)->company,
        'street' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'country_code' => $faker->valid($countryCodeValidator)->countryCode,
        'email' => $faker->optional(0.8)->email,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'language_code' => $faker->optional(0.6)->languageCode,
        'created_at' => $faker->dateTimeBetween('-5 years', 'now'),
    ];
});

$factory->define(Donation::class, function (Faker $faker) {
    $baseCurrency = config('fundraising.base_currency');
    $currencyValidator = function ($cc) use ($baseCurrency) {
        return in_array($cc, array_merge(config('fundraising.currencies'), [$baseCurrency]));
    };
    $date = $faker->dateTimeBetween('-5 years', 'now');
    $amount = $faker->numberBetween(1, 10000);
    $currency = $faker->valid($currencyValidator)->currencyCode;
    $exchAmount = $currency != $baseCurrency ? EzvExchangeRates::getExchangeRate($currency) * $amount : $amount;
    return [
        'date' => $date,
        'amount' => $amount,
        'currency' => $currency,
        'exchange_amount' => $exchAmount,
        'channel' => $faker->randomElement(['Cash', 'Bank Transfer', 'PayPal', 'Stripe', 'RaiseNow']),
        'purpose' => $faker->optional(0.2)->sentence,
        'reference' => $faker->optional(0.2)->bothify('********************'),
        'in_name_of' => $faker->optional(0.1)->name,
        'thanked' => $faker->optional(0.1)->dateTimeBetween($date, 'now'),
    ];
});

