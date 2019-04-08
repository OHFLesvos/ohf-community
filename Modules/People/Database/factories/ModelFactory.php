<?php

use Modules\People\Entities\Person;

use Faker\Generator as Faker;

use Carbon\Carbon;

$factory->define(Person::class, function (Faker $faker) {
    // $faker = \Faker\Factory::create('ar_JO');

    $gender = $faker->randomElement(['male', 'female']);

    $lc = $faker->optional(0.3)->languageCode;
    $language = $lc != null ? collect(\Languages::lookup([$lc]))->first() : null;

    $countryCode = $faker->optional(0.9)->countryCode; 
    $nationality = $countryCode != null && !in_array($countryCode, ['HM', 'BV']) ? \Countries::getOne($countryCode) : null;

    $dob = $faker->optional(0.9)->dateTimeBetween('-70 years', 'now');

    return [
        'name' => $faker->firstName($gender),
        'family_name' => $faker->lastName,
        'police_no' => $faker->optional(0.6)->numberBetween(10000,99999),
        'case_no' => $faker->optional(0.6)->numberBetween(10000,99999),
        'registration_no' => $faker->optional(0.15)->numberBetween(10000,99999),
        'section_card_no' => $faker->optional(0.05)->numberBetween(10000,99999),
        'nationality' => $nationality,
        'languages' => $language,
        'gender' => $gender == 'female' ? 'f' : 'm',
        'date_of_birth' => $dob != null ? Carbon::instance($dob) : null,
    ];
});
