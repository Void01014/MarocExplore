<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:api')->prefix('itineraries')->group(function(){
    Route::get('', [ItineraryController::class, 'index']);
    Route::post('', [ItineraryController::class, 'create']);
    Route::get('{id}', [ItineraryController::class, 'show']);
    Route::put('{id}', [ItineraryController::class, 'update']);
    Route::delete('{id}', [ItineraryController::class, 'destroy']);
    Route::post('{id}/wishlist', [ItineraryController::class, 'addToWishlist']);
    Route::delete('{id}/wishlist', [ItineraryController::class, 'removeFromWishlist']);
});
