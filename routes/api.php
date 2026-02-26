<?php

use App\Http\Controllers\Api\BikeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['throttle:60,1'])
    ->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/featured', [ProductController::class, 'featured']);
        Route::get('/products/{product}', [ProductController::class, 'show']);

        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);

        Route::get('/bikes/manufacturers', [BikeController::class, 'manufacturers']);
        Route::get('/bikes/models', [BikeController::class, 'models']);
        Route::get('/bikes/years', [BikeController::class, 'years']);
        Route::get('/bikes/show', [BikeController::class, 'show']);
    });
