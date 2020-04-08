<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use Faker\Generator as Faker;

$factory->define(MoneyTransaction::class, fn (Faker $faker) => [
    'receipt_no' => $faker->unique()->numberBetween(1, 10000),
    'date' => $faker->dateTimeBetween('-5 years', 'now'),
    'type' => $faker->randomElement(['income', 'spending']),
    'amount' => $faker->randomFloat(2, 1, 2000),
    'beneficiary' => $faker->name,
    'category' => $faker->catchPhrase,
    'project' => $faker->optional(0.7)->catchPhrase,
    'description' => $faker->sentence,
    'booked' => false,
]);

$factory->define(Wallet::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
