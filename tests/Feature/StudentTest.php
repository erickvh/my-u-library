<?php

namespace Tests\Feature;

use App\Models\Book\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getToken('librarian');
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ]);
        $this->seed();
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

    public function test_api_list_students()
    {
        $response = $this->get('/api/students');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
                'updated_at',
            ]
        ]);
    }


    public function test_api_get_borred_books()
    {
        $id = Role::where('name', 'student')->first()->users()->first()->id;
        $response = $this->get("/api/students/$id/books");


        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'author',
                'genre_id',
                'created_at',
                'updated_at',
            ]
        ]);
    }


    public function test_api_return_book_success()
    {
        $id = Role::where('name', 'student')->first()->users()->first()->id;
        $book_id = Role::where('name', 'student')->first()->users()->first()->books()->first()->id;

        $response = $this->post("/api/students/$id/books/$book_id");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message'
        ]);
    }


    public function test_api_return_book_fail()
    {
        $id = Role::where('name', 'student')->first()->users()->first()->id;
        $book_id = 1000;

        $response = $this->post("/api/students/$id/books/$book_id");
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_api_return_book_fail_user()
    {
        $id = User::latest()->first()->id + 1;

        $book_id = Book::latest()->first()->id;

        $response = $this->post("/api/students/$id/books/$book_id");
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message'
        ]);
    }


    public function test_api_show_my_books()
    {
        $response = $this->get("/api/students/my-books");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'author',
                'genre_id',
                'created_at',
                'updated_at',
            ]
        ]);
    }
}
