<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\People\Person;
use Carbon\Carbon;
use Faker\Generator as Faker;

$countries = weightedCountries(15);

$factory->define(Person::class, function (Faker $faker) use ($countries) {
    // $faker = \Faker\Factory::create('ar_JO');

    $gender = $faker->optional(0.9)->randomElement(['male', 'female']);

    $lc = $faker->optional(0.3)->languageCode;
    $language = $lc != null ? \Languages::lookup([$lc])->first() : null;

    $nationality = $faker->optional(0.9)->randomElement($countries);

    $dob = $faker->optional(0.9)->dateTimeBetween('-70 years', 'now');

    return [
        'name' => $faker->firstName($gender),
        'family_name' => $faker->lastName,
        'nickname' => $faker->optional(0.05)->firstName($gender),
        'police_no' => $faker->optional(0.6)->numberBetween(10000,99999),
        'nationality' => $nationality,
        'languages_string' => $language,
        'gender' => $gender != null ? ($gender == 'female' ? 'f' : 'm') : null,
        'date_of_birth' => $dob != null ? Carbon::instance($dob) : null,
        'remarks' => $faker->optional(0.05)->sentence,
        'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
    ];
});
