<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryLending;
use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class LibraryLendingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LibraryLending::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lending_date = $this->faker->dateTimeBetween('-24 months', 'now');
        $return_date = (new Carbon($lending_date))->addDays(Arr::random([14, 21, 28]));
        $returned_date = mt_rand(0, 100) > 20 ? $this->faker->dateTimeBetween($lending_date, 'now') : null;
        return [
            'person_id' => Person::inRandomOrder()->select('id')->limit(1)->first(),
            'lending_date' => $lending_date,
            'return_date' => $return_date,
            'returned_date' => $returned_date,
        ];
    }
}
