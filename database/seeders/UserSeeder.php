<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->count(10)->create();
        $roles = Role::all();
        $users = User::all();

        foreach ($users as $user) {
            $user->assignRole($roles->random());
        }


        User::create([
            'first_name' => 'Librarian',
            'last_name' => 'Librarian',
            'email' => 'librarian@u.test',
            'password' => bcrypt('password')
        ])->assignRole('librarian');


        User::create([
            'first_name' => 'student',
            'last_name' => 'student',
            'email' => 'student@u.test',
            'password' => bcrypt('password')
        ])->assignRole('student');
    }
}
