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
        return [
            'name' => $this->faker->name($gender),
            'id_number' => $this->faker->optional(0.6)->numberBetween(10000, 99999),
            'gender' => $gender,
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-1 month'),
            'nationality' => $this->faker->optional(0.95)->country,
            'living_situation' => $this->faker->optional(0.6)->city,
            'liability_form_signed' => $this->faker->optional(0.2)->date(),
            // 'purpose_of_visit' => $this->faker->bs,
        ];
    }
}
