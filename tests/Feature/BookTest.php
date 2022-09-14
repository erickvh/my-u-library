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
        $this->token = $this->getToken('student');

        $this->seed(GenreSeeder::class);
        $this->seed(BookSeeder::class);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);
    }

    public function getToken($role)
    {
        $email = $role . '@test';
        $user = User::create([
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => $email,
            'password' => 'password'
        ]);
        $user->assignRole($role);
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

    public function test_api_genre()
    {
        $token = $this->getToken('librarian');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);



        $response = $this->getJson('/api/genres');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);
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
        $this->token = $this->getToken('librarian');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);

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


    public function test_api_create_book_error()
    {
        $this->token = $this->getToken('librarian');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);

        $genre = Genre::first();
        $response = $this->postJson('/api/books', [
            'description' => 'Test',
            'author' => '',
            'genre_id' => $genre->id,
            'published_year' => 2021,
            'stock' => 10
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'title',
                'author'
            ]
        ]);
    }


    public function test_api_checkout_success()
    {
        $book = Book::first();
        $response = $this->postJson("/api/books/$book->id/checkout");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_api_checkout_out_of_stock()
    {
        $book = Book::first();
        $book->stock = 0;
        $book->save();

        $response = $this->postJson("/api/books/$book->id/checkout");

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_api_checkout_not_found()
    {
        $book = Book::orderBy('id', 'desc')->first();
        $response = $this->postJson("/api/books/" . ($book->id + 1) . "/checkout");

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message'
        ]);
    }
}
