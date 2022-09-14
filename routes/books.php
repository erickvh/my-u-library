<?php

use App\Http\Controllers\Book\BookController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/books', [BookController::class, 'index'])->middleware('permission:book.index'); // get books
    Route::get('/books/{id}', [BookController::class, 'show'])->middleware('permission:book.show'); // get book
    Route::post('/books', [BookController::class, 'create'])->middleware('permission:book.create'); // create book
    Route::post('/books/{id}/checkout', [BookController::class, 'checkout'])->middleware('permission:book.checkout'); // checkout book
});
