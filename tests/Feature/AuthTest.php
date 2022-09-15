<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
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
        $user = User::create(
            [
                'first_name' => 'test',
                'last_name' => 'test',
                'email' => 'librarian@test',
                'password' => bcrypt('test')
            ]
        );
        $this->token = $user->createToken('token')->plainTextToken;
        $user->assignRole(Role::where('name', 'librarian')->first());
        $user->save();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    public function test_api_register_when_success()
    {

        $student = Role::where('name', 'student')->first();
        $response = $this->postJson('/api/register', [
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => 'test@email.com',
            'password' => 'passwordABC',
            'role_id' => $student->id
        ]);


        $response->assertStatus(201);
        $response->assertJsonStructure([

            'data' => [
                'id',
                'first_name',
                'last_name',
                'email'
            ]
        ]);
    }

    public function test_api_register_when_error()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'test'
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'first_name',
                'last_name',
                'email',
                'password'
            ]
        ]);
    }

    public function test_api_login_when_success()
    {
        $body = [
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => 'test@email.com',
            'password' => bcrypt('passwordABC')
        ];
        User::create($body);

        $response = $this->postJson('/api/login', [
            'email' => $body['email'],
            'password' => 'passwordABC'
        ]);



        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
    }


    public function test_api_login_when_fail_credentials()
    {
        $body = [
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => 'test@email.com',
            'password' => bcrypt('passwordABC')
        ];
        User::create($body);

        $response = $this->postJson('/api/login', [
            'email' => $body['email'],
            'password' => 'passwordABD'
        ]);



        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_api_login_when_fail_user_not_found()
    {


        $response = $this->postJson('/api/login', [
            'email' => "email@notfound.test",
            'password' => 'passwordABC'
        ]);

        $response->assertStatus(401);
    }

    public function test_api_roles_success()
    {
        $response = $this->getJson('/api/roles');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
            ]
        ]);
    }

    public function test_api_roles_fail()
    {
        $user = User::create(
            [
                'first_name' => 'test',
                'last_name' => 'test',
                'email' => 'student@test',
                'password' => bcrypt('test')
            ]
        );
        $token = $user->createToken('token')->plainTextToken;
        $user->assignRole(Role::where('name', 'student')->first());
        $user->save();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ]);
        $response = $this->getJson('/api/roles');
        $response->assertStatus(403);
    }
    public function test_api_logout_when_success()
    {
        $body = [
            'first_name' => 'Jonh',
            'last_name' => 'Doe',
            'email' => 'test@email.com',
            'password' => bcrypt('passwordABC')
        ];

        User::create($body);


        $response = $this->postJson('/api/login', [
            'email' => $body['email'],
            'password' => 'passwordABC'
        ]);
        $token = $response->json('token');

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/logout', [
            'email' => $body['email'],
            'password' => 'passwordABC'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message'
        ]);
    }
}
