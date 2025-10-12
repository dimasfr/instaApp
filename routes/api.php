<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']); // list semua post (default tanpa soft deleted)
    Route::get('/all', [PostController::class, 'all']); // list semua termasuk soft deleted
    Route::get('/{post}', [PostController::class, 'show']); // detail post

    // Routes yang butuh auth
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [PostController::class, 'store']); // create post
        Route::put('/{post}', [PostController::class, 'update']); // update post
        Route::delete('/{post}', [PostController::class, 'destroy']); // soft delete
        Route::post('/{post}/restore', [PostController::class, 'restore']); // restore soft deleted
        Route::delete('/{post}/force', [PostController::class, 'forceDelete']); // hard delete
    });
});
