<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendProductController;

Route::prefix('frontend')->group(function () {
    Route::get('/products', [FrontendProductController::class, 'products']);
    Route::get('/categories', [FrontendProductController::class, 'categories']);
    Route::get('/highlights', [FrontendProductController::class, 'highlights']);
    Route::get('/products/{product}', [FrontendProductController::class, 'product']);
    Route::get('/categories/{category}', [FrontendProductController::class, 'category']);
    Route::get('/settings', [FrontendProductController::class, 'settings']);
    Route::get('/home', [FrontendProductController::class, 'home']);
});