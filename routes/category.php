<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::resource('categories', CategoryController::class);
});

Route::prefix('api')->middleware(['auth'])->group(function () {
  Route::apiResource('categories', CategoryController::class);
});