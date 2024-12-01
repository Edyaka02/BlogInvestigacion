<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController; 


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas publicas
Route::get('/', [ArticuloController::class, 'index'])->name('articulos.articulo');
Route::get('/articulos/{id}', [ArticuloController::class, 'show'])->name('articulo.show');