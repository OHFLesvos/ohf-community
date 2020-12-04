<?php

namespace Database\Factories\Bank;

use App\Models\Bank\CouponHandout;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponHandoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CouponHandout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('-24 months', 'now'),
            'amount' => mt_rand(1, 2),
        ];
    }
}
