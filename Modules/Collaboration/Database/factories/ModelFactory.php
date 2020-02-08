<?php

use Modules\Collaboration\Entities\CalendarResource;
use Modules\Collaboration\Entities\CalendarEvent;

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

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(CalendarResource::class, function (Faker\Generator $faker) {
    return [
        'title' => ucfirst($faker->unique()->word),
        'color' => $faker->unique()->hexcolor,
        'group' => ucfirst($faker->optional(0.3)->word),
    ];
});

$factory->state(CalendarResource::class, 'default', [
    'default' => true,
]);

$factory->define(CalendarEvent::class, function (Faker\Generator $faker) {
    $start_date = Carbon::today()->addDays(mt_rand(-45, 45))->addHours(mt_rand(8, 19));
    $end_date = (clone $start_date)->addHours(mt_rand(1, 3));
    $description = $faker->optional(0.4)->paragraphs(mt_rand(1, 4));
    return [
        'title' => $faker->sentence(mt_rand(1, 7)),
        'description' => $description != null ? implode("\n", $description) : null,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'user_id' => \App\User::all()->pluck('id')->random(),
    ];
});

$factory->afterCreating(CalendarResource::class, function ($resource, $faker) {
    $resource->events()->saveMany(factory(CalendarEvent::class, mt_rand(0, 150))->make());
});
