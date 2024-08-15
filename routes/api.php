<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id_books}', [BookController::class, 'byId']);
    Route::put('/books/{id_books}', [BookController::class, 'update']);
    Route::delete('/books/{id_books}', [BookController::class, 'destroy']);
});