<?php

use App\Models\Library\LibraryBook;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(LibraryBook::class, function (Faker\Generator $faker) {
    $lc = $faker->optional(0.8)->languageCode;
    $language = $lc != null ? collect(\Languages::lookup([$lc]))->first() : null;
    return [
        'title' => $faker->sentence,
        'author' => $faker->optional(0.9)->name,
        'language' => $language,
        'isbn' => $faker->optional(0.6)->isbn10,
    ];
});
