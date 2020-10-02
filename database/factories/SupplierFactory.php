<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Accounting\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'category' => $faker->optional(0.9)->catchPhrase,
        'address' => $faker->optional(0.7)->address,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'mobile' => $faker->optional(0.5)->phoneNumber,
        'email' => $faker->optional(0.5)->email,
        'tax_number' => $faker->optional(0.5)->numberBetween(100000000, 999999999),
        'bank' => $faker->optional(0.5)->company,
        'iban' => $faker->optional(0.5)->iban('GR'),
    ];
});
