<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bank\CouponHandout;
use Faker\Generator as Faker;

$factory->define(CouponHandout::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('-24 months', 'now'),
        'amount' => mt_rand(1, 2),
    ];
});
