<?php

namespace Database\Factories\Visitors;

use App\Models\Visitors\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->optional(0.9)->randomElement(['male', 'female']);
        $entered_at = $this->faker->dateTimeBetween('-3 months', 'now');
        $left_at = $this->faker->optional(0.9)->dateTimeBetween($entered_at, (new Carbon($entered_at))->addMinutes(90));
        $type = $this->faker->randomElement(['visitor', 'participant', 'staff', 'external']);
        return [
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName,
            'id_number' => $this->faker->optional(0.6)->numberBetween(10000,99999),
            'place_of_residence' => $this->faker->optional(0.6)->city,
            'entered_at' => $entered_at,
            'left_at' => $left_at,
            'type' => $type,
            'organization' => $type == 'staff' || $type == 'external' ? $this->faker->company : null,
            'activity' => $type == 'participant' ? $this->faker->bs : null,
        ];
    }
}
