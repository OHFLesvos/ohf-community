<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Faker\Generator as Faker;

$factory->define(CommunityVolunteer::class, function (Faker $faker) {
    $start_date = $faker->dateTimeBetween('-24 months', 'now');
    return [
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
    ];
});

$factory->define(Responsibility::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
        'capacity' => $faker->optional(0.7)->numberBetween(1, 6),
        'available' => mt_rand(0, 100) > 30,
    ];
});
