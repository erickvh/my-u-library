<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Permission::create(['name' => 'book.index']);
        Permission::create(['name' => 'book.show']);
        Permission::create(['name' => 'book.create']);
        Permission::create(['name' => 'book.checkout']);
        Permission::create(['name' => 'student.index']);
        Permission::create(['name' => 'student.getBooks']);
        Permission::create(['name' => 'student.returnBook']);
        Permission::create(['name' => 'student.myBooks']);
        // complementary resources
        Permission::create(['name' => 'genre.index']);
        Permission::create(['name' => 'role.index']);

        Role::where('name', 'student')->first()->givePermissionTo([
            'book.index',
            'book.show',
            'book.checkout',
            'student.myBooks',
        ]);

        Role::where('name', 'librarian')->first()->givePermissionTo([
            'book.index',
            'student.myBooks',
            'book.show',
            'book.create',
            'genre.index',
            'role.index',
            'student.index',
            'student.getBooks',
            'student.returnBook',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'book.index')->delete();
        Permission::where('name', 'book.show')->delete();
        Permission::where('name', 'book.create')->delete();
        Permission::where('name', 'book.checkout')->delete();
        Permission::where('name', 'student.index')->delete();
        Permission::where('name', 'student.getBooks')->delete();
        Permission::where('name', 'student.returnBook')->delete();
    }
};
