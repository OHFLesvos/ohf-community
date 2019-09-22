<?php

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

use App\Support\Facades\PermissionRegistry;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** 
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
    ];
});

$factory->define(App\RolePermission::class, function (Faker\Generator $faker) {
    $keys = PermissionRegistry::keys();
    return [
        'key' => $keys[array_rand($keys)],
    ];
});

/** 
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

