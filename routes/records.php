<?php

use App\Http\Controllers\RecordsController;
use App\Http\Controllers\Api\RecordController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::resource('tickets', RecordsController::class);
});

Route::prefix('api')->middleware(['auth'])->group(function () {
  Route::apiResource('tickets', RecordController::class);
});
