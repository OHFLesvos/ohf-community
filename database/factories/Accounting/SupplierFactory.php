<?php

namespace Database\Factories\Accounting;

use App\Models\Accounting\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company,
            'category' => $this->faker->optional(0.9)->catchPhrase,
            'address' => $this->faker->optional(0.7)->address,
            // place_id TODO
            'phone' => $this->faker->optional(0.5)->phoneNumber,
            'mobile' => $this->faker->optional(0.5)->phoneNumber,
            'email' => $this->faker->optional(0.5)->email,
            'website' => $this->faker->optional(0.5)->url,
            'tax_number' => $this->faker->optional(0.5)->numberBetween(100000000, 999999999),
            'tax_office' => $this->faker->optional(0.5)->city,
            'bank' => $this->faker->optional(0.5)->company,
            'iban' => $this->faker->optional(0.5)->iban('GR'),
            'remarks' => $this->faker->optional(0.05)->sentence,
        ];
    }
}
