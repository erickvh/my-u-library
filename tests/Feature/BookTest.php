<?php

namespace Tests\Feature;

use App\Models\Book\Book;
use App\Models\Book\Genre;
use App\Models\User;
use Database\Seeders\BookSeeder;
use Database\Seeders\GenreSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected $token = null;


    public function setup(): void
    {
        parent::setup();
        $this->token = $this->getToken();

        $this->seed(GenreSeeder::class);
        $this->seed(BookSeeder::class);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);
    }

    public function getToken()
    {

        $user = User::create([
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        return $user->createToken('token')->plainTextToken;
    }


    public function test_api_paginated_books_success()
    {
        $response = $this->getJson('/api/books');


        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'genre_id',
                    'created_at',
                    'updated_at',
                    'genre' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
    }





    public function test_api_show_book_success()
    {

        $response = $this->getJson('/api/books/' . Book::first()->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [

                'id',
                'title',
                'description',
                'author',
                'genre',
                'created_at',
                'updated_at'
            ]
        );
    }

    public function test_api_show_book_when_error()
    {
        $lastBook = Book::orderBy('id', 'desc')->first();
        $response = $this->getJson('/api/books/' . ($lastBook->id + 1));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_api_create_book_success()
    {
        $genre = Genre::first();
        $response = $this->postJson('/api/books', [
            'title' => 'Test',
            'description' => 'Test',
            'author' => 'Test',
            'genre_id' => $genre->id,
            'published_year' => 2021,
            'stock' => 10
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([

            'id',
            'title',
            'description',
            'author',
            'genre_id',
            'created_at',
            'updated_at'

        ]);
    }
}
