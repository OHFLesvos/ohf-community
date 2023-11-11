<?php

namespace Database\Factories\Visitors;

use App\Models\Visitors\VisitorCheckin;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorCheckinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VisitorCheckin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'checkin_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'purpose_of_visit' => $this->faker->optional(0.8)->randomElement(['Class', 'Childcare', 'Food', 'Clothes', 'Meeting']),
        ];
    }
}
