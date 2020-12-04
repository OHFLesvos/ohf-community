<?php

namespace Database\Factories\CommunityVolunteers;

use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponsibilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Responsibility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->jobTitle,
            'description' => $this->faker->realText($this->faker->numberBetween(300, 1000)),
            'capacity' => $this->faker->optional(0.7)->numberBetween(1, 6),
            'available' => mt_rand(0, 100) > 30,
        ];
    }
}
