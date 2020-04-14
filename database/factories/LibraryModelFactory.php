<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use Carbon\Carbon;

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

$factory->define(LibraryLending::class, function (Faker\Generator $faker) use ($language_codes) {
    $lending_date = $faker->dateTimeBetween('-24 months', 'now');
    $return_date = (new Carbon($lending_date))->addDays(Arr::random([14, 21, 28]));
    $returned_date = mt_rand(0, 100) > 20 ? $faker->dateTimeBetween($lending_date, 'now') : null;
    return [
        'person_id' => Person::inRandomOrder()->select('id')->limit(1)->first(),
        'lending_date' => $lending_date,
        'return_date' => $return_date,
        'returned_date' => $returned_date,
    ];
});
