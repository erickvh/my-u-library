<?php

namespace Database\Factories\Book;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(["Fantasy", "Horror", "Romance", "Science Fiction", "Thriller", "Western"]),
            'description' => $this->faker->paragraph(1),
        ];
    }
}
