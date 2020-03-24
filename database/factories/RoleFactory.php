<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use App\RolePermission;
use App\Support\Facades\PermissionRegistry;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->jobTitle,
    ];
});

$factory->define(RolePermission::class, function (Faker $faker) {
    $keys = PermissionRegistry::keys();
    return [
        'key' => $keys[array_rand($keys)],
    ];
});
