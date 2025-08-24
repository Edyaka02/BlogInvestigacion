<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArticuloController;
use App\Http\Controllers\API\EventoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas API para artículos
Route::prefix('articulos')->group(function () {
    Route::get('/', [ArticuloController::class, 'index']);          // GET /api/articulos
    Route::post('/', [ArticuloController::class, 'store']);         // POST /api/articulos
    Route::get('/{id}', [ArticuloController::class, 'show']);       // GET /api/articulos/{id}
    Route::put('/{id}', [ArticuloController::class, 'update']);     // PUT /api/articulos/{id}
    Route::delete('/{id}', [ArticuloController::class, 'destroy']); // DELETE /api/articulos/{id}
});

// Rutas API para eventos
Route::prefix('eventos')->group(function () {
    Route::get('/', [EventoController::class, 'index']);          // GET /api/eventos
    Route::post('/', [EventoController::class, 'store']);         // POST /api/eventos
    Route::get('/{id}', [EventoController::class, 'show']);       // GET /api/eventos/{id}
    Route::put('/{id}', [EventoController::class, 'update']);     // PUT /api/eventos/{id}
    Route::delete('/{id}', [EventoController::class, 'destroy']); // DELETE /api/eventos/{id}
});