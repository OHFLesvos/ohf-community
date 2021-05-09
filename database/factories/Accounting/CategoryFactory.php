<?php

namespace Database\Factories\Accounting;

use App\Models\Accounting\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->catchPhrase,
            'description' => $this->faker->optional(0.7)->sentence,
            'enabled' => $this->faker->boolean(90),
        ];
    }
}
