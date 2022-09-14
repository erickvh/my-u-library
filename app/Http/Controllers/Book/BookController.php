<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book\Book;
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

    public function create(Request $request)
    {
        $book = Book::create($request->all());

        return response()->json($book, 201);
    }
}
