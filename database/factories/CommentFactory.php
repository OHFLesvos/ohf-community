<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
    ];
});

$factory->afterMaking(Comment::class, function (Comment $comment, $faker) {
    $rnd = mt_rand(0, 100);
    if ($rnd < 75) {
        $user = User::inRandomOrder()->limit(1)->first();
        if ($user !== null) {
            $comment->setUser($user);
        }
    } else if ($rnd < 95) {
        $comment->user_name = $faker->name;
    }
});
