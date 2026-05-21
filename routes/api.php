<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\PreferencesController;
use App\Http\Controllers\Api\StatController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/iniciar-sesion', [AuthController::class, 'login']);
Route::get('/estadisticas/global', [StatController::class, 'global']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cerrar-sesion', [AuthController::class, 'logout']);
    Route::get('/yo', [AuthController::class, 'me']);
    Route::put('/usuario/preferencias', [PreferencesController::class, 'update']);

    Route::get('/estadisticas/yo', [StatController::class, 'me']);
    Route::get('/estadisticas-anuales', [StatController::class, 'statistics']);

    Route::apiResource('usuarios', UserController::class)
        ->only(['index', 'show', 'update', 'destroy'])
        ->parameters(['usuarios' => 'user']);

    Route::apiResource('peliculas', MovieController::class)
        ->parameters(['peliculas' => 'movie']);
});
