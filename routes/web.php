<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PremioController;
use App\Http\Controllers\ProyectoController;


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
    return view('entities.inicio.inicio');
})->name('inicio');

// Articulos
Route::get('/articulos', [ArticuloController::class, 'index'])->name('articulos.index');
Route::get('/articulos/{id}/download', [ArticuloController::class, 'download'])->name('articulo.download');
Route::get('/articulos/{id}', [ArticuloController::class, 'show'])->name('articulo.show');

// Libros
Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
Route::get('/libros/{id}/download', [LibroController::class, 'download'])->name('libros.download');
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');

// Eventos
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.evento');
Route::get('/eventos/filtrar', [EventoController::class, 'filtrar'])->name('eventos.filtrar');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('evento.show');

// Proyectos
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/{id}/download', [ProyectoController::class, 'download'])->name('proyectos.download');
Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');


Route::prefix('dashboard')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/basurero', [AdminController::class, 'basurero'])->name('basurero');

    // ------------------- Articulos -------------------
    Route::get('/articulos', [ArticuloController::class, 'adminIndex'])->name('articulos.index');
    Route::post('/articulos', [ArticuloController::class, 'store'])->name('articulos.store');
    Route::put('/articulos/{id}', [ArticuloController::class, 'update'])->name('articulos.update');
    Route::delete('/articulos/{id}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');

    // ------------------- Libros ----------------------
    Route::get('/libros', [LibroController::class, 'adminIndex'])->name('libros.index');
    Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
    Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');

    // ------------------- Eventos -------------------
    Route::get('/eventos', [EventoController::class, 'adminIndex'])->name('eventos.index');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // ------------------- Premios -------------------
    Route::get('/premios', [PremioController::class, 'adminIndex'])->name('premios.index');
    Route::post('/premios', [PremioController::class, 'store'])->name('premios.store');
    Route::put('/premios/{id}', [PremioController::class, 'update'])->name('premios.update');
    Route::delete('/premios/{id}', [PremioController::class, 'destroy'])->name('premios.destroy');

    // ------------------- Proyectos -------------------
    Route::get('/proyectos', [ProyectoController::class, 'adminIndex'])->name('proyectos.index');
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::put('/proyectos/{id}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');
});
