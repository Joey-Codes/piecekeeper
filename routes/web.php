<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\SheetMusicController;
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
        Route::get('/dashboard/today', [DashboardController::class, 'today']);
        Route::get('/dashboard/history', [DashboardController::class, 'history']);
        Route::post('/dashboard/sessions/{session}/toggle-piece', [DashboardController::class, 'togglePiece']);
        Route::post('/dashboard/sessions/{session}/finish', [DashboardController::class, 'finish']);
        Route::put('/dashboard/sessions/{session}', [DashboardController::class, 'update']);
        Route::get('/pieces', [PieceController::class, 'index']);
        Route::post('/pieces', [PieceController::class, 'store']);
        Route::put('/pieces/reorder', [PieceController::class, 'reorder']);
        Route::put('/pieces/{piece}', [PieceController::class, 'update']);
        Route::delete('/pieces/{piece}', [PieceController::class, 'destroy']);
        Route::post('/pieces/{piece}/sheet-music', [SheetMusicController::class, 'upload']);
        Route::get('/pieces/{piece}/sheet-music/{filename}', [SheetMusicController::class, 'show']);
        Route::delete('/pieces/{piece}/sheet-music', [SheetMusicController::class, 'destroy']);
        Route::get('/insights', [InsightsController::class, 'index']);
        Route::get('/insights/calendar', [InsightsController::class, 'calendar']);
        Route::get('/insights/session', [InsightsController::class, 'session']);
    });
});

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
