<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Accounting\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'category' => $faker->optional(0.9)->catchPhrase,
        'address' => $faker->optional(0.7)->address,
        // place_id TODO
        'phone' => $faker->optional(0.5)->phoneNumber,
        'mobile' => $faker->optional(0.5)->phoneNumber,
        'email' => $faker->optional(0.5)->email,
        'website' => $faker->optional(0.5)->url,
        'tax_number' => $faker->optional(0.5)->numberBetween(100000000, 999999999),
        'tax_office' => $faker->optional(0.5)->city,
        'bank' => $faker->optional(0.5)->company,
        'iban' => $faker->optional(0.5)->iban('GR'),
        'remarks' => $faker->optional(0.05)->sentence,
    ];
});
