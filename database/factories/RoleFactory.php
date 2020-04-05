<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use App\RolePermission;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
    ];
});
$factory->afterCreating(Role::class, function ($role, $faker) {
    $keys = array_keys(config('auth.permissions'));
    $selected_keys = Arr::random($keys, mt_rand(0, min(10, count($keys))));
    $permissions = collect($selected_keys)
        ->map(fn ($key) => (new RolePermission())->withKey($key));
    $role->permissions()->saveMany($permissions);
});
