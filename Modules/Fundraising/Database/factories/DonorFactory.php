<?php

use Modules\Fundraising\Entities\Donor;

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
