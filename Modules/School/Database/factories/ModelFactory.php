<?php

use Modules\School\Entities\SchoolClass;

use Carbon\Carbon;

use Faker\Generator as Faker;

$factory->define(SchoolClass::class, function (Faker $faker) {
    $start_date = $faker->dateTimeBetween('-3 months', '1 month');
    $end_date = $faker->dateTimeBetween($start_date, '3 months');
    return [
        'name' => $faker->catchPhrase,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'teacher_name' => $faker->name,
        'capacity' => $faker->numberBetween(5, 30),
    ];
});
