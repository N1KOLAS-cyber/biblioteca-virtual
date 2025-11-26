<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Gestión de roles (solo admin)
Route::middleware(['role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
});

// Gestión de usuarios (admin y staff)
Route::resource('users', UserController::class);

// Gestión de autores (solo admin)
Route::middleware(['role:admin'])->group(function () {
    Route::resource('authors', AuthorController::class);
});

// Gestión de categorías (solo admin)
Route::middleware(['role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

// Gestión de libros (solo admin)
Route::middleware(['role:admin'])->group(function () {
    Route::resource('books', BookController::class);
});

// Gestión de planes (admin y staff)
Route::resource('plans', PlanController::class);
