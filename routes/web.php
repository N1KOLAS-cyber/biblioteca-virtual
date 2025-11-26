<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserDashboardController;

// Página principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catálogo público
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/libros/{book:slug}', [BookController::class, 'show'])->name('books.show');

// Rutas autenticadas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard de usuario
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Lectura de libros
    Route::get('/libros/{book:slug}/leer', [BookController::class, 'read'])->name('books.read');
    
    // Favoritos
    Route::post('/libros/{book}/favorito', [BookController::class, 'toggleFavorite'])->name('books.toggleFavorite');
});
