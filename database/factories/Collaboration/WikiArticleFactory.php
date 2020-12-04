<?php

namespace Database\Factories\Collaboration;

use App\Models\Collaboration\WikiArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

class WikiArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WikiArticle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->catchPhrase,
            'content' => implode("\n\n", $this->faker->paragraphs(4)),
        ];
    }
}
