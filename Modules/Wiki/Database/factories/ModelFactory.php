<?php

use Modules\Wiki\Entities\WikiArticle;

use Faker\Generator as Faker;

$factory->define(WikiArticle::class, function (Faker $faker) {
    return [
        'title' => $faker->catchPhrase,
        'content' => implode("\n\n", $faker->paragraphs(4)),
    ];
});
