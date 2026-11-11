<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/', BookController::class);
Route::get('/posts', [BookController::class, "percobaan"]);
