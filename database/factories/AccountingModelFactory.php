<?php

use App\Models\Accounting\MoneyTransaction;

use Faker\Generator as Faker;

$factory->define(MoneyTransaction::class, function (Faker $faker) {
    return [
        'receipt_no' => $faker->unique()->numberBetween(1, 10000),
        'date' => $faker->dateTimeBetween('-5 years', 'now'),
        'type' => $faker->randomElement(['income', 'spending']),
        'amount' => $faker->randomFloat(2, 1, 2000),
        'beneficiary' => $faker->name,
        'category' => $faker->catchPhrase,
        'project' => $faker->optional(0.7)->catchPhrase,
        'description' => $faker->sentence,
        'booked' => false,
    ];
});
