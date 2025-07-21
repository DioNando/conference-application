<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User routes
    Route::prefix('users')->group(function () {
        // Standard CRUD operations
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);

        // Additional user operations
        Route::get('/search/query', [UserController::class, 'search']);
        Route::get('/stats/overview', [UserController::class, 'statistics']);
        Route::put('/{user}/password', [UserController::class, 'updatePassword']);
        Route::patch('/{user}/preferences', [UserController::class, 'updatePreferences']);
    });

    // Event routes
    Route::prefix('events')->group(function () {
        // Standard CRUD operations
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::get('/{event}', [EventController::class, 'show']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);

        // Additional event operations
        Route::get('/search/query', [EventController::class, 'search']);
        Route::get('/stats/overview', [EventController::class, 'statistics']);
        Route::patch('/{event}/toggle-active', [EventController::class, 'toggleActive']);
        Route::get('/{event}/dates', [EventController::class, 'getDates']);
    });
});
