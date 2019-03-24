<?php

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Entities\Donation;

use Faker\Generator as Faker;

$factory->define(Donor::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    return [
        'salutation' => $faker->title($gender),
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'company' => $faker->optional(0.2)->company,
        'street' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'country_code' => $faker->countryCode,
        'email' => $faker->optional(0.8)->email,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'language' => $faker->optional(0.3)->languageCode,
    ];
});

$factory->define(Donation::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-5 years', 'now');
    $currency = $faker->currencyCode;
    $amount = $faker->numberBetween(1, 10000);
    return [
        'date' => $date,
        'amount' => $amount,
        'currency' => $currency,
        'exchange_amount' => $amount, // TODO relate to currency
        'channel' => $faker->randomElement(['Cash', 'Bank Transfer', 'PayPal', 'Stripe', 'RaiseNow']),
        'purpose' => $faker->optional(0.2)->sentence,
        'reference' => $faker->optional(0.2)->uuid,
        'in_name_of' => $faker->optional(0.1)->name,
        // 'thanked' => TODO
    ];
});

