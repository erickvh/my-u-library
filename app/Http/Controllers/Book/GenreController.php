<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::all();

        return response()->json($genres);
    }
}
