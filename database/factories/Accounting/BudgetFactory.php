<?php

namespace Database\Factories\Accounting;

use App\Models\Accounting\Budget;
use App\Models\Fundraising\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Budget::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-5 years', 'now');

        return [
            'name' => $this->faker->catchPhrase,
            'description' => $this->faker->optional(0.7)->sentence,
            'agreed_amount' => $this->faker->randomFloat(2, 1, 50000),
            'initial_amount' => $this->faker->randomFloat(2, 1, 50000),
            'donor_id' => Donor::factory(),
            'is_completed' => $this->faker->boolean(10),
            'created_at' => $date,
            'closed_at' => $this->faker->optional(0.1)->dateTimeBetween($date, 'now'),
        ];
    }
}
