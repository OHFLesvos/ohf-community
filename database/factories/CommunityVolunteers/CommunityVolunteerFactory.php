<?php

namespace Database\Factories\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityVolunteerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommunityVolunteer::class;

    private array $countries = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->optional(0.9)->randomElement(['male', 'female']);

        $lc = $this->faker->optional(0.3)->languageCode;
        $language = $lc != null ? \Languages::lookup([$lc])->first() : null;

        if (empty($this->countries)) {
            $this->countries = weightedCountries(10);
        }
        $nationality = $this->faker->optional(0.9)->randomElement($this->countries);

        $dob = $this->faker->optional(0.9)->dateTimeBetween('-70 years', 'now');

        return [
            'first_name' => $this->faker->firstName($gender),
            'family_name' => $this->faker->lastName,
            'nickname' => $this->faker->optional(0.05)->firstName($gender),
            'police_no' => $this->faker->optional(0.6)->numberBetween(10000,99999),
            'nationality' => $nationality,
            'languages_string' => $language,
            'gender' => $gender != null ? ($gender == 'female' ? 'f' : 'm') : null,
            'date_of_birth' => $dob != null ? Carbon::instance($dob) : null,
            'local_phone' => $this->faker->optional(0.8)->phoneNumber,
            'other_phone' => $this->faker->optional(0.2)->phoneNumber,
            'whatsapp' => $this->faker->optional(0.8)->phoneNumber,
            'email' => $this->faker->optional(0.1)->freeEmail,
            'skype' => $this->faker->optional(0.1)->userName,
            'residence' => $this->faker->optional(0.8)->city,
            'pickup_location' => $this->faker->optional(0.6)->city,
            'notes' => $this->faker->optional(0.1)->sentence,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
