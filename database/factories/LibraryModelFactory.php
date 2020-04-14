<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Library\LibraryBook;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$language_codes = weightedLanguages(15);
$factory->define(LibraryBook::class, function (Faker\Generator $faker) use ($language_codes) {
    $sentence = $faker->sentence;
    return [
        'title' => substr($sentence, 0, strlen($sentence) - 1),
        'author' => $faker->optional(0.9)->name,
        'language_code' => $faker->optional(0.3)->randomElement($language_codes),
        'isbn' => $faker->optional(0.6)->isbn10,
    ];
});
