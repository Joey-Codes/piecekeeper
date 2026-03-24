<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/csrf-cookie', fn () => response()->noContent())->name('csrf-cookie');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::middleware('auth')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'show']);
            Route::put('/', [UserController::class, 'update']);
        });
        Route::get('/pieces', [PieceController::class, 'index']);
        Route::post('/pieces', [PieceController::class, 'store']);
        Route::put('/pieces/{piece}', [PieceController::class, 'update']);
        Route::delete('/pieces/{piece}', [PieceController::class, 'destroy']);
    });
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
