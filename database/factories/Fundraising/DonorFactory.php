<?php

namespace Database\Factories\Fundraising;

use App\Models\Fundraising\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $countryCodeValidator = function ($cc) {
            return ! in_array($cc, ['HM', 'BV']); // not supported by country code library
        };
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'salutation' => $this->faker->title($gender),
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName,
            'company' => $this->faker->optional(0.2)->company,
            'street' => $this->faker->streetAddress,
            'zip' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country_code' => $this->faker->valid($countryCodeValidator)->countryCode,
            'email' => $this->faker->optional(0.8)->email,
            'phone' => $this->faker->optional(0.5)->phoneNumber,
            'language_code' => $this->faker->optional(0.6)->languageCode,
            'created_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
