<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Library\LibraryBook;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(LibraryBook::class, function (Faker\Generator $faker) {
    $sentence = $faker->sentence;
    return [
        'title' => substr($sentence, 0, strlen($sentence) - 1),
        'author' => $faker->optional(0.9)->name,
        'language_code' => $faker->optional(0.8)->languageCode,
        'isbn' => $faker->optional(0.6)->isbn10,
    ];
});
