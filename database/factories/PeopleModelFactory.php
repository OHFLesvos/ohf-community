<?php

use App\Models\People\Person;

use Faker\Generator as Faker;

use Carbon\Carbon;

$countries = weightedCountries(10);

$factory->define(Person::class, function (Faker $faker) use($countries) {
    // $faker = \Faker\Factory::create('ar_JO');

    $gender = $faker->optional(0.9)->randomElement(['male', 'female']);

    $lc = $faker->optional(0.3)->languageCode;
    $language = $lc != null ? collect(\Languages::lookup([$lc]))->first() : null;

    $nationality = $faker->optional(0.9)->randomElement($countries);

    $dob = $faker->optional(0.9)->dateTimeBetween('-70 years', 'now');

    return [
        'name' => $faker->firstName($gender),
        'family_name' => $faker->lastName,
        'police_no' => $faker->optional(0.6)->numberBetween(10000,99999),
        'nationality' => $nationality,
        'languages' => $language,
        'gender' => $gender != null ? ($gender == 'female' ? 'f' : 'm') : null,
        'date_of_birth' => $dob != null ? Carbon::instance($dob) : null,
        'remarks' => $faker->optional(0.05)->sentence,
        'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
    ];
});

function weightedCountries($num)
{
    $countries = \Countries::getList('en');
    $rand_keys = array_rand($countries, $num);
    $percentages = randomPercentages($num);
    $data = [];
    while (count($percentages) > 0) {
        $p = array_pop($percentages);
        $c = array_pop($rand_keys);
        for ($i = 0; $i < $p; $i++) {
            $data[] = \Countries::getOne($c);
        }
    }
    return $data;
}
