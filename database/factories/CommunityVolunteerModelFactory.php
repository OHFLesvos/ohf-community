<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Carbon\Carbon;
use Faker\Generator as Faker;

$countries = weightedCountries(10);

$factory->define(CommunityVolunteer::class, function (Faker $faker) use ($countries) {
    $gender = $faker->optional(0.9)->randomElement(['male', 'female']);

    $lc = $faker->optional(0.3)->languageCode;
    $language = $lc != null ? \Languages::lookup([$lc])->first() : null;

    $nationality = $faker->optional(0.9)->randomElement($countries);

    $dob = $faker->optional(0.9)->dateTimeBetween('-70 years', 'now');

    $start_date = $faker->dateTimeBetween('-24 months', 'now');
    return [
        'first_name' => $faker->firstName($gender),
        'family_name' => $faker->lastName,
        'nickname' => $faker->optional(0.05)->firstName($gender),
        'police_no' => $faker->optional(0.6)->numberBetween(10000,99999),
        'nationality' => $nationality,
        'languages_string' => $language,
        'gender' => $gender != null ? ($gender == 'female' ? 'f' : 'm') : null,
        'date_of_birth' => $dob != null ? Carbon::instance($dob) : null,
        'work_starting_date' => $start_date,
        'work_leaving_date' => $faker->optional()->dateTimeBetween($start_date, 'now'),
        'local_phone' => $faker->optional(0.8)->phoneNumber,
        'other_phone' => $faker->optional(0.2)->phoneNumber,
        'whatsapp' => $faker->optional(0.8)->phoneNumber,
        'email' => $faker->optional(0.1)->freeEmail,
        'skype' => $faker->optional(0.1)->userName,
        'residence' => $faker->optional(0.8)->city,
        'pickup_location' => $faker->optional(0.6)->city,
        'notes' => $faker->optional(0.1)->sentence,
        'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
    ];
});

$factory->define(Responsibility::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
        'description' => $faker->realText($faker->numberBetween(300, 1000)),
        'capacity' => $faker->optional(0.7)->numberBetween(1, 6),
        'available' => mt_rand(0, 100) > 30,
    ];
});
