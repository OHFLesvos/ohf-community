<?php

namespace Database\Factories\Accounting;

use App\Models\Accounting\MoneyTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoneyTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MoneyTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'receipt_no' => $this->faker->unique()->numberBetween(1, 10000),
            'date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'type' => $this->faker->randomElement(['income', 'spending']),
            'amount' => $this->faker->randomFloat(2, 1, 2000),
            'attendee' => $this->faker->name,
            'description' => $this->faker->sentence,
            'booked' => false,
        ];
    }
}
