<?php

use Modules\Helpers\Entities\Helper;

use Faker\Generator as Faker;

use Carbon\Carbon;

$factory->define(Helper::class, function (Faker $faker) {
    $responsibilities = [];
    for ($i = 0; $i < mt_rand(0, 3); $i++) {
        $responsibilities[] = $faker->jobTitle;
    }
    $start_date = $faker->dateTimeBetween('-24 months', 'now');
    return [
        'responsibilities' => $responsibilities,
        'work_starting_date' => $start_date,
        'work_leaving_date' => $faker->optional()->dateTimeBetween($start_date, 'now'),
    ];
});
