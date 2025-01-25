<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController; 
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;


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

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/articulos', [ArticuloController::class, 'adminIndex'])->name('articulos.index');
    Route::get('/articulos/create', [ArticuloController::class, 'create'])->name('articulos.create');
    Route::post('/articulos', [ArticuloController::class, 'store'])->name('articulos.store');
    Route::get('/articulos/{id}/edit', [ArticuloController::class, 'edit'])->name('articulos.edit');
    Route::put('/articulos/{id}', [ArticuloController::class, 'update'])->name('articulos.update');
    Route::delete('/articulos/{id}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');
    Route::post('/articulos/{id}/restore', [ArticuloController::class, 'restore'])->name('articulos.restore');
    
});