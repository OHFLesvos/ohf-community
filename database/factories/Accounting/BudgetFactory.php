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
        return [
            'name' => $this->faker->catchPhrase,
            'description' => $this->faker->optional(0.7)->sentence,
            'amount' => $this->faker->randomFloat(2, 1, 50000),
            'donor_id' => Donor::factory(),
        ];
    }
}
