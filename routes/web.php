<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\EventoController;


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
Route::get('/', function () {
    return view('inicio');
})->name('inicio');
// Route::get('/')->name('inicio');
Route::get('/articulos', [ArticuloController::class, 'index'])->name('articulos.articulo');
Route::get('/articulos/{id}', [ArticuloController::class, 'show'])->name('articulo.show');

// Libros
Route::get('/libros', [LibroController::class, 'index'])->name('libros.libro');
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libro.show');

// Eventos
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.evento');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('evento.show');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/basurero', [AdminController::class, 'basurero'])->name('basurero');

    // ------------------- Articulos -------------------

    Route::get('/articulos', [ArticuloController::class, 'adminIndex'])->name('articulos.index');
    Route::get('/articulos/create', [ArticuloController::class, 'create'])->name('articulos.create');
    Route::post('/articulos', [ArticuloController::class, 'store'])->name('articulos.store');
    // Route::get('/articulos/{id}/edit', [ArticuloController::class, 'edit'])->name('articulos.edit');
    Route::put('/articulos/{id}', [ArticuloController::class, 'update'])->name('articulos.update');
    Route::delete('/articulos/{id}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');
    Route::post('/articulos/{id}/restore', [ArticuloController::class, 'restore'])->name('articulos.restore');
    // Route::delete('/articulos/{id}', [ArticuloController::class, 'forceDelete'])->name('articulos.forceDelete');

    // ------------------- Libros -------------------

    Route::get('/libros', [LibroController::class, 'adminIndex'])->name('libros.index');
    Route::get('/libros/create', [LibroController::class, 'create'])->name('libros.create');
    Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
    // Route::get('/libros/{id}/edit', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');
    Route::post('/libros/{id}/restore', [LibroController::class, 'restore'])->name('libros.restore');

    // ------------------- Eventos -------------------

    Route::get('/eventos', [EventoController::class, 'adminIndex'])->name('eventos.index');
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    // Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::post('/eventos/{id}/restore', [EventoController::class, 'restore'])->name('eventos.restore');
});
