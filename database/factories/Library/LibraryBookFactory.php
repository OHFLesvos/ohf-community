<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibraryBookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LibraryBook::class;

    private array $language_codes = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sentence = $this->faker->sentence;
        if (empty($this->language_codes)) {
            $this->language_codes = weightedLanguages(15);
        }
        return [
            'title' => substr($sentence, 0, strlen($sentence) - 1),
            'author' => $this->faker->optional(0.9)->name,
            'language_code' => $this->faker->optional(0.3)->randomElement($this->language_codes),
            'isbn' => $this->faker->optional(0.6)->isbn10,
        ];
    }
}
