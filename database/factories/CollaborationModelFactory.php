<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Collaboration\WikiArticle;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(WikiArticle::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->catchPhrase,
        'content' => implode("\n\n", $faker->paragraphs(4)),
    ];
});
