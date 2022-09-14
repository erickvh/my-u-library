<?php

namespace Database\Seeders;

use App\Models\Book\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            'Science Fiction',
            'Fantasy',
            'Horror',
            'Romance',
            'Mystery',
            'Thriller',
            'Historical Fiction',
            'Biography',
            'Autobiography',
            'Memoir',
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
            ]);
        }
    }
}
