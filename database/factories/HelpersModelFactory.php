<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Helpers\Helper;
use App\Models\Helpers\Responsibility;
use Faker\Generator as Faker;

$factory->define(Helper::class, function (Faker $faker) {
    $start_date = $faker->dateTimeBetween('-24 months', 'now');
    return [
        'work_starting_date' => $start_date,
        'work_leaving_date' => $faker->optional()->dateTimeBetween($start_date, 'now'),
    ];
});

$factory->define(Responsibility::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
        'capacity' => $faker->optional(0.7)->numberBetween(1, 6),
        'available' => mt_rand(0, 100) > 30,
    ];
});