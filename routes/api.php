<?php

use App\Http\Controllers\TranslationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('translations/export', [TranslationsController::class, 'export']);
    Route::get('translations/search', [TranslationsController::class, 'search']);
    Route::apiResource('translations', TranslationsController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
