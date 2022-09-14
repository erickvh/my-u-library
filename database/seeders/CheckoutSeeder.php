<?php

namespace Database\Seeders;

use App\Models\Book\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $books = Book::all();

        foreach ($users as $user) {
            $user->books()->attach(
                $books->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
