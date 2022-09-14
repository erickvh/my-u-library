<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->middleware('permission:student.index'); // get students
    Route::get('/{id}/books', [StudentController::class, 'getBooks'])->middleware('book.getBooks'); // get books of a student
    Route::post('/{id}/books/{book_id}', [StudentController::class, 'returnBook'])->middleware('permission:student.returnBook'); /// return a book
    Route::get('/my-books', [StudentController::class, 'getMyBooks'])->middleware('permission:student.myBooks'); // get books of a student

});
