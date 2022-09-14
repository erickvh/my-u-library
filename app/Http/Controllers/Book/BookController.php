<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book\Book;
use App\Models\Book\Genre;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('genre')->author($request->author)->title($request->title)->genre($request->genre)->paginate(10);

        return response()->json($books);
    }

    public function show(Request $request)
    {
        $book = Book::with('genre')->find($request->id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    public function create(BookRequest $request)
    {
        $book = Book::create($request->all());

        return response()->json($book, 201);
    }


    public function checkout(Request $request)
    {
        $book = Book::find($request->id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->stock <= 0) {
            return response()->json(['message' => 'Book out of stock'], 400);
        }

        $book->users()->attach($request->user()->id);
        $book->stock -= 1;
        $book->save();

        return response()->json(['data' => $book, 'message' => 'Book checked out successfully']);
    }
}
