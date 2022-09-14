<?php

namespace Database\Factories\Book;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText($maxNbChars = 40, $indexSize = 2),
            'author' => $this->faker->name(),
            'description' => $this->faker->sentence(3),
            'published_year' => $this->faker->year(),
            'stock' => $this->faker->numberBetween(0, 100),
            'genre_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
