<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getBooks($id)
    {
        $user = User::with('books')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        return response()->json($user->books);
    }

    public function returnBook($id, $book_id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $book = $user->books()
            ->find($book_id);

        if (!$book) {
            // if book is not found
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->pivot->status = 'returned';
        $book->pivot->save();

        $book->stock = $book->stock + 1;
        $book->save();

        return response()->json(['message' => 'Book returned successfully']);
        return response()->json();
    }

    public function getMyBooks()
    {
        $user = auth()->user();
        $user = User::with('books')->find($user->id);

        return response()->json($user->books);
    }
}
