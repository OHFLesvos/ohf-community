<?php

namespace Database\Factories\Visitors;

use App\Models\Visitors\Visitor;
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
        $memberNumber = $this->faker->optional(0.6)->numberBetween(100000, 999999);

        return [
            'name' => $this->faker->name($gender),
            'id_number' => $this->faker->optional(0.6)->numberBetween(10000, 99999),
            'membership_number' => $memberNumber !== null ? strtoupper($this->faker->randomLetter) . $memberNumber : null,
            'gender' => $gender,
            'date_of_birth' => $this->faker->dateTimeBetween('-70 years', '-1 month'),
            'nationality' => $this->faker->optional(0.95)->country,
            'living_situation' => $this->faker->optional(0.6)->city,
            'liability_form_signed' => $this->faker->optional(0.2)->date(),
            'remarks' => $this->faker->optional(0.2)->text(),
        ];
    }
}
