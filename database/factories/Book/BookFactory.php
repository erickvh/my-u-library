<?php

namespace Database\Factories\Book;

use App\Models\Book\Genre;
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
        $genres = Genre::take(10)->get();

        $genresIds = $genres->pluck('id');



        return [
            'title' => $this->faker->realText($maxNbChars = 40, $indexSize = 2),
            'author' => $this->faker->name(),
            'description' => $this->faker->sentence(3),
            'published_year' => $this->faker->year(),
            'stock' => $this->faker->numberBetween(0, 100),
            'genre_id' => $this->faker->numberBetween($genresIds->first(), $genresIds->last()),
        ];
    }
}
